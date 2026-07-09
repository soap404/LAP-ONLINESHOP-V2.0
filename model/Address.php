<?php

require_once "DB.php";

class Address extends DB
{

    public function getUserAddresses($user_id): array
    {
        $sql = "SELECT * FROM addresses WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public function createAddress($data, $user_id)
    {
        $sql = "INSERT INTO addresses (user_id, city, zip, address_line1, address_line2, type)
            VALUES (:user_id, :city, :zip, :address_line1, :address_line2, :type)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':zip', $data['zip']);
        $stmt->bindParam(':address_line1', $data['address_line1']);
        $stmt->bindParam(':address_line2', $data['address_line2']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->execute();
        return true;
    }


}