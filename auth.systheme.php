<?php

class Database {
    private $host = "localhost";
    private $db_name = "location_poo";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}


class User {
    private $conn;
    private $table_name = "users";

    public $id_user;
    public $name;
    public $email;
    public $password;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . 
                " (name, email, password, role) VALUES
                (:name, :email, :password, :role)";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = 'user'; // Default role

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }

    public function emailExists() {
        $query = "SELECT id_user, name, password, role FROM " . $this->table_name . 
                " WHERE email = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_user = $row['id_user'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }

    public function updateLastActivity() {
        $query = "UPDATE " . $this->table_name . 
                " SET updated_at = NOW() 
                  WHERE id_user = :id_user";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_user", $this->id_user);
        return $stmt->execute();
    }
}

// models/Session.php
class Session {
    private const INACTIVE_TIMEOUT = 1800; // 30 minutes in seconds
    
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        self::init();
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        self::init();
        session_destroy();
    }

    public static function checkInactivity() {
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > self::INACTIVE_TIMEOUT)) {
            self::destroy();
            return true;
        }
        $_SESSION['last_activity'] = time();
        return false;
    }

    public static function isAdmin() {
        return self::get('role') === 'admin';
    }
}

// controllers/AuthController.php
class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($name, $email, $password, $password_confirm) {
        if($password !== $password_confirm) {
            return ["success" => false, "message" => "Les mots de passe ne correspondent pas"];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Format d'email invalide"];
        }

        if(strlen($password) < 8) {
            return ["success" => false, "message" => "Le mot de passe doit contenir au moins 8 caractères"];
        }

        $this->user->name = $name;
        $this->user->email = $email;
        $this->user->password = $password;

        if($this->user->emailExists()) {
            return ["success" => false, "message" => "Cet email existe déjà"];
        }

        if($this->user->create()) {
            return ["success" => true, "message" => "Inscription réussie"];
        }

        return ["success" => false, "message" => "Échec de l'inscription"];
    }

    public function login($email, $password) {
        $this->user->email = $email;

        if($this->user->emailExists() && 
           password_verify($password, $this->user->password)) {
            
            Session::set('user_id', $this->user->id_user);
            Session::set('name', $this->user->name);
            Session::set('email', $email);
            Session::set('role', $this->user->role);
            Session::set('last_activity', time());

            return ["success" => true, "message" => "Connexion réussie"];
        }

        return ["success" => false, "message" => "Email ou mot de passe incorrect"];
    }

    public function logout() {
        Session::destroy();
        return ["success" => true, "message" => "Déconnexion réussie"];
    }

    public function checkAuth() {
        if(Session::checkInactivity()) {
            return false;
        }

        if(Session::get('user_id')) {
            $this->user->id_user = Session::get('user_id');
            $this->user->updateLastActivity();
            return true;
        }

        return false;
    }

    public function requireAuth() {
        if(!$this->checkAuth()) {
            header('Location: login.php');
            exit;
        }
    }

    public function requireAdmin() {
        $this->requireAuth();
        if(!Session::isAdmin()) {
            header('Location: index.php');
            exit;
        }
    }
}