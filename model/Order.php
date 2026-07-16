<?php

require_once "DB.php";

class Order extends DB
{

    public function create($data, $user_id): void
    {
        $order_sql = "INSERT INTO orders (user_id, status, delivery_id, invoice_id)
                VALUES (:user_id, 1 , :delivery_id, :invoice_id)";
        $order_stmt = $this->conn->prepare($order_sql);
        $order_stmt->bindParam(':user_id', $user_id);
        $order_stmt->bindParam(':delivery_id', $data['delivery_id']);
        $order_stmt->bindParam(':invoice_id', $data['invoice_id']);
        $order_stmt->execute();
        $order_id = $this->conn->lastInsertId();

        foreach ($data['products'] as $product) {
            $price_sql = "SELECT price FROM products WHERE id = :product_id";
            $price_stmt = $this->conn->prepare($price_sql);
            $price_stmt->bindParam(':product_id', $product['id']);
            $price_stmt->execute();
            $price = $price_stmt->fetch()['price'];

            $items_sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (:order_id, :product_id, :quantity, :price)";
            $items_stmt = $this->conn->prepare($items_sql);
            $items_stmt->bindParam(':order_id', $order_id);
            $items_stmt->bindParam(':product_id', $product['id']);
            $items_stmt->bindParam(':quantity', $product['quantity']);
            $items_stmt->bindParam(':price', $price);
            $items_stmt->execute();
        }

    }
}