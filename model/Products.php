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


}