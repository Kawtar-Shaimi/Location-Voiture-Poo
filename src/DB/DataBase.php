<?php 

namespace User\LocationPoo\DB;

class DataBase{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'password';
    private $databse = 'Location_POO';
    public $conn;

    public function __construct(){
        $this->conn = mysqli_connect($this->host,$this->user,$this->pass, $this->databse);
        if(!$this->conn){
            echo "Could not Connect";
        }
    }
}