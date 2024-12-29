<?php 

namespace User\LocationPoo\Models;

use User\LocationPoo\DB\DataBase;
use User\LocationPoo\Models\Validator;
use Exception;

class User{
    private $db;

    public function __construct(){
        $this->db = new DataBase();
    }

    public function getUsers(){
        try{
            $sql = "SELECT * FROM users";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getUser($id){
        try{
            $sql = "SELECT * FROM users WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            $this->db->conn->close();
            return $user;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createUser($name, $email, $pass, $confirmPass, $role){

        if(!Validator::validateCreateUser($name, $email, $pass, $confirmPass, $role, $this->db->conn)){
            header("Location: /Location_POO/pages/Admin/Users/createUser.php");
            exit;
        }

        try{
            $hashPass = password_hash($pass,PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $hashPass, $role);
            if($stmt->execute()){
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "User created successfully";
                header("Location: /Location_POO/pages/Admin/Users/index.php");
                exit;
            }else{
                $_SESSION["message"] = "Error creating user";
                header("Location: /Location_POO/pages/Admin/Users/createUser.php");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function updateUser($id, $name, $email, $oldEmail, $role){

        if(!Validator::validateUpdateUser($name, $email, $oldEmail, $role, $this->db->conn)){
            header("Location: /Location_POO/pages/Admin/Users/updateUser.php?id=$id");
            exit;
        }

        try{
            $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $role, $id);
            if($stmt->execute()){
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "User updated successfully";
                header("Location: /Location_POO/pages/Admin/Users/index.php");
                exit;
            }else{
                $_SESSION["message"] = "Error updating user";
                header("Location: /Location_POO/pages/Admin/Users/updateUser.php?id=$id");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteUser($id){
        try{
            $sql = "DELETE FROM users WHERE id_user = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->close();
                $this->db->conn->close();
                $_SESSION["message"] = "User deleted successfully";
                header("Location: /Location_POO/pages/Admin/Users/index.php");
                exit;
            }else{
                $_SESSION["message"] = "Error deleting user";
                header("Location: /Location_POO/pages/Admin/Users/index.php");
                exit;
            }
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getUsersIds(){
        try{
            $sql = "SELECT id_user FROM users WHERE role = 'user'";
            $result = $this->db->conn->query($sql);
            return $result;
        }catch(Exception $e){
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}