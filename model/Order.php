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

    public function admin_index(): array
    {
        $sql = "SELECT 
                    o.id AS order_id,
                    u.email AS user_email,
                    o.datetime AS date,
                    SUM(oi.price * oi.quantity) AS total_price,
                    o.status
                FROM orders o
                LEFT JOIN users u ON u.id = o.user_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                GROUP BY o.id";
        $orders_stm = $this->conn->prepare($sql);
        $orders_stm->execute();
        return $orders_stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function user_index($user_id): array
    {
        $sql = "SELECT 
                    o.id AS order_id,
                    o.datetime AS date,
                    SUM(oi.price * oi.quantity) AS total_price,
                    o.status
                FROM orders o
                LEFT JOIN users u ON u.id = o.user_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = :user_id
                GROUP BY o.id
                ";
        $orders_stm = $this->conn->prepare($sql);
        $orders_stm->bindParam(':user_id', $user_id);
        $orders_stm->execute();
        return $orders_stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_status($order_id, $status): void
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
    }

    public function show($order_id): bool|array
    {
        $sql = "SELECT * FROM orders WHERE id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function order_details($order_id): bool|array
    {
        $sql = "SELECT 
        oi.price AS price,
        oi.quantity AS quantity,
        p.name AS name,
        p.img_name AS img_name
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}