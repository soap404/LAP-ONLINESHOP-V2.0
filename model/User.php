<?php

require_once "DB.php";
class User extends DB
{

    public function register($data): void
    {
        $email = $data['email'];
        $password = $data['password'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];

        $sql = "INSERT INTO users (email, password, first_name, last_name)
                VALUES (:email, :password, :first_name, :last_name)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->execute();
    }

    public function getUserByEmail($email) : array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}