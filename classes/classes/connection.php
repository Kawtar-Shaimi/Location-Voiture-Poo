<?php
// $host = 'localhost';
// $user = 'root';
// $password = 'Ren-ji24';
// $db = 'location_poo';

// $conn = new mysqli($host,$user,$password,$db);
// if($conn->connect_error){
//     die("connection failed" . $conn->connection_error);
// }





class Database {
    private $host = 'localhost';
    private $dbname = 'location_poo';
    private $user = 'root';
    private $pass = 'Ren-ji24';

    public function connect() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        $pdo = new PDO($dsn, $this->user, $this->pass);

        return $pdo;
    }
}


?>
