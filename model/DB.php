<?php

class DB{

    private string $host;

    private string $user;

    private string $password;

    private string $dbname;

    protected PDO $conn;

    public function __construct(){
        $this->host = "127.0.0.1:3310";
        $this->user = "root";
        $this->password = "";
        $this->dbname = "onlineshop";

        $this->connect();
    }

    private function connect(): void
    {
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}