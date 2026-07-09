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

        $sql = "INSERT INTO users (email, password, first_name, last_name, is_admin)
                VALUES (:email, :password, :first_name, :last_name, 0)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->execute();
    }

    public function getUserByEmail($email): bool|array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $id): bool|array
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser(array $data, int $id): bool
    {
        $email = $data['email'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];

        $sql = "UPDATE users
        SET first_name = :first_name,
            last_name = :last_name,
            email = :email
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return true;
    }

    public function updateUserPassword(string $password, int $id): bool
    {
        $sql = "UPDATE users
        SET password = :password
        WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return true;
    }


}