<?php 

namespace User\LocationPoo\Models;

class Validator{

    public static function validateCsrf(){
        if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            echo "Session expired or invalid request. Please refresh the page.";
            exit();
        }
        unset($_SESSION['csrf_token']);
    }

    public static function validateAdmin(){
        if((!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) && (!isset($_COOKIE['user_id']) || !isset($_COOKIE['user_role']))){
            header("Location: /Location_POO/pages/Auth/login.php");
            exit;
        }else{
            if(isset($_COOKIE['user_role'])){
                if($_COOKIE['user_role'] != "admin"){
                    header("Location: /Location_POO/index.php");
                    exit;
                }
            }else{
                if($_SESSION['user_role'] != "admin"){
                    header("Location: /Location_POO/index.php");
                    exit;
                }
            }
        }
    }

    public static function validateLogedInUser(){
        if((!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) && (!isset($_COOKIE['user_id']) || !isset($_COOKIE['user_role']))){
            header("Location: /Location_POO/pages/Auth/login.php");
            exit;
        }
    }

    public static function validateLogedOutUser(){
        if ((isset($_COOKIE['user_id']) && isset($_COOKIE['user_role'])) || (isset($_SESSION['user_id']) && isset($_SESSION['user_role']))) {
            if(isset($_COOKIE['user_role'])){
                if($_COOKIE['user_role'] === "user"){
                    header("Location: /Location_POO/index.php");
                    exit;
                }else {
                    header("Location: /Location_POO/pages/Admin/Users/index.php");
                }
            }else{
                if($_SESSION['user_role'] === "user"){
                    header("Location: /Location_POO/index.php");
                    exit;
                }else {
                    header("Location: /Location_POO/pages/Admin/Users/index.php");
                }
            }
        }
    }

    public static function validateLogin($email, $pass){

        $isValid = true;

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }
        
        return $isValid;
    }

    public static function validateSignup($name, $email, $pass, $confirmPass, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                $_SESSION["emailErr"]="This email already existe";
                $isValid = false;
            }
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }

        if(empty($confirmPass)){
            $_SESSION["confirmPassErr"] = "Confirm password is required.";
        }else{
            if($pass != $confirmPass){
                $_SESSION["confirmPassErr"] = "Passwords are Not matching!";
                $isValid = false;
            }
        }

        return $isValid;
    }

    public static function validateCreateUser($name, $email, $pass, $confirmPass, $role, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                $_SESSION["emailErr"]="This email already existe";
                $isValid = false;
            }
        }

        if (empty($pass) || strlen($pass) < 8) {
            $_SESSION["passErr"] = "Password is required and must be strong.";
            $isValid = false;
        }

        if(empty($confirmPass)){
            $_SESSION["confirmPassErr"] = "Confirm password is required.";
        }else{
            if($pass != $confirmPass){
                $_SESSION["confirmPassErr"] = "Passwords are Not matching!";
                $isValid = false;
            }
        }

        if (empty($role) || ($role != "user" && $role != "admin")) {
            $_SESSION["roleErr"] = "Role is required and must be user or admin.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateUpdateUser($name, $email, $oldEmail, $role, $conn){

        $isValid = true;

        if (empty($name) || strlen($name) > 30) {
            $_SESSION['nameErr'] = "Name is required and must be 30 characters or less.";
            $isValid = false;
        }

        if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Email is required and must be 100 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT email FROM users where email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                if($result->fetch_column() != $oldEmail){
                    $_SESSION["emailErr"] = "This email already existe";
                    $isValid = false;
                }
            }
        }

        if (empty($role) || ($role != "user" && $role != "admin")) {
            $_SESSION["roleErr"] = "Role is required and must be user or admin.";
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateCreateVoiture($numImmatriculation, $marque, $modele, $annee, $conn){

        $isValid = true;

        if (empty($numImmatriculation) || strlen($numImmatriculation) > 10) {
            $_SESSION['numImmatriculationErr'] = "NumImmatriculation is required and must be 10 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT NumImmatriculation FROM voitures where NumImmatriculation = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $numImmatriculation);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                $_SESSION["numImmatriculationErr"]="This NumImmatriculation already existe";
                $isValid = false;
            }
        }

        if (empty($marque) || strlen($marque) > 50) {
            $_SESSION['marqueErr'] = "Marque is required and must be 50 characters or less.";
            $isValid = false;
        }

        if (empty($modele) || strlen($modele) > 50) {
            $_SESSION['modeleErr'] = "Modele is required and must be 50 characters or less.";
            $isValid = false;
        }

        if (empty($annee) || $annee > date("Y") || $annee < 1900) {
            $_SESSION['anneeErr'] = "Annee is required and must be between 1900 and ".date("Y");
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateUpdateVoiture($id_voiture, $numImmatriculation, $marque, $modele, $annee, $oldNumImmatriculation, $conn){

        $isValid = true;

        if (empty($numImmatriculation) || strlen($numImmatriculation) > 10) {
            $_SESSION['numImmatriculationErr'] = "NumImmatriculation is required and must be 10 characters or less.";
            $isValid = false;
        }else{
            $sql = "SELECT NumImmatriculation FROM voitures where id_voiture = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_voiture);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows >0){
                if($result->fetch_column() != $oldNumImmatriculation){
                    $_SESSION["numImmatriculationErr"]="This NumImmatriculation already existe";
                    $isValid = false;
                }
            }
        }

        if (empty($marque) || strlen($marque) > 50) {
            $_SESSION['marqueErr'] = "Marque is required and must be 50 characters or less.";
            $isValid = false;
        }

        if (empty($modele) || strlen($modele) > 50) {
            $_SESSION['modeleErr'] = "Modele is required and must be 50 characters or less.";
            $isValid = false;
        }

        if (empty($annee) || $annee > date("Y") || $annee < 1900) {
            $_SESSION['anneeErr'] = "Annee is required and must be between 1900 and ".date("Y");
            $isValid = false;
        }

        return $isValid;
    }

    public static function validateContratInfo($id_user, $id_voiture, $date_debut, $date_fin){

        $isValid = true;

        if (empty($id_user)) {
            $_SESSION['idUserErr'] = "User is required.";
            $isValid = false;
        }

        if (empty($id_voiture)) {
            $_SESSION['idVoitureErr'] = "Voiture is required.";
            $isValid = false;
        }

        if (empty($date_debut) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $date_debut)) {
            $_SESSION['dateDebutErr'] = "Date debut is required.";
            $isValid = false;
        }

        if (empty($date_fin) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $date_fin)) {
            $_SESSION['dateFinErr'] = "Date fin is required.";
            $isValid = false;
        }

        if($date_debut > $date_fin){
            $_SESSION['dateDebutErr'] = "Date debut must be less than date fin.";
            $isValid = false;
        }

        return $isValid;
    }

}