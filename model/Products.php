<?php

require_once "DB.php";
class Products extends DB
{
    public function allActive() : bool | array
    {
        $sql = "SELECT * FROM products WHERE is_active = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all() : bool | array
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id) : bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getById(int $id) : array | bool
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data) : bool
    {
     $sql = "INSERT INTO products (name, description, price, stock, is_active, img_name) VALUES (:name, :description, :price, :stock, :is_active , :img_name)";
     $stmt = $this->conn->prepare($sql);
     $stmt->bindParam(":name", $data["name"]);
     $stmt->bindParam(":description", $data["description"]);
     $stmt->bindParam(":price", $data["price"]);
     $stmt->bindParam(":stock", $data["stock"]);
     $stmt->bindParam(":is_active", $data["is_active"]);
     $stmt->bindParam(":img_name", $data["img_name"]);
     return $stmt->execute();
    }

    public function update(int $id, array $data) : bool
    {
        $sql = "UPDATE products SET
        name = :name,
        description = :description,
        price = :price,
        stock = :stock,
        is_active = :is_active,
        img_name = :img_name
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $data["name"]);
        $stmt->bindParam(":description", $data["description"]);
        $stmt->bindParam(":price", $data["price"]);
        $stmt->bindParam(":stock", $data["stock"]);
        $stmt->bindParam(":is_active", $data["is_active"]);
        $stmt->bindParam(":img_name", $data["img_name"]);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }


}