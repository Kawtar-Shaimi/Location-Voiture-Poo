<?php 

namespace User\LocationPoo\Models;

use User\LocationPoo\DB\DataBase;
use User\LocationPoo\Models\Validator;
use Exception;

class Auth{
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function signup($name, $email, $pass, $confirmPass, $remember){

        if(!Validator::validateSignup($name, $email, $pass, $confirmPass, $this->db->conn)){
            header("Location: /Location_POO/pages/Auth/signup.php");
            exit;
        }

        try{
            $hashPass = password_hash($pass,PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashPass);
            $stmt->execute();
            $user_id = $stmt->insert_id;

            if ($remember) {
                setcookie("user_id", $user_id, time() + 30 * 24 * 3600, "/","",true,true);
                setcookie("user_role", "user", time() + 30 * 24 * 3600, "/","",true,true);
            }else{
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_role"] = "user";
            }

            $stmt->close();
            $this->db->conn->close();

            header("Location: /Location_POO/index.php");
            exit;
            
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function login($email, $pass, $remember){

        if(!Validator::validateLogin($email, $pass)){
            header("Location: /Location_POO/pages/Auth/login.php");
            exit;
        }

        try{
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($pass, $user["password"])) {
                    if ($remember) {
                        setcookie("user_id", $user["id_user"], time() + 30 * 24 * 3600, "/","",true,true);
                        setcookie("user_role", $user["role"], time() + 30 * 24 * 3600, "/","",true,true);
                    }else{
                        $_SESSION["user_id"] = $user["id_user"];
                        $_SESSION["user_role"] = $user["role"];
                    }
                    
                    $stmt->close();
                    $this->db->conn->close();
                    
                    if ($user["role"] === "user") {
                        header("Location: /Location_POO/index.php");
                    } else {
                        header("Location: /Location_POO/pages/Admin/Users/index.php");
                    }
                    exit;
                }else{
                    $_SESSION["loginErr"] = "Email or Password is incorrect!";
                    $stmt->close();
                    $this->db->conn->close();
                    header("Location: /Location_POO/pages/Auth/login.php");
                    exit;
                }
            }else{
                $_SESSION["loginErr"] = "Email or Password is incorrect!";
                $stmt->close();
                $this->db->conn->close();
                header("Location: /Location_POO/pages/Auth/login.php");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function logout(){
        try {
            setcookie("user_id", "", time() - 3600, "/");
            setcookie("user_role", "", time() - 3600, "/");
            unset($_SESSION["user_id"], $_SESSION["user_role"]);
            header("Location: /Location_POO/pages/Auth/login.php");
            exit;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}