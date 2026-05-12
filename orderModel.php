<?php

// Include database connection file
require_once __DIR__ . "/../config/db.php";

// Model class for handling Order operations (MVC - Model layer)
class OrderModel {

    // Database connection variable
    private $conn;

    // Constructor to initialize database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get all orders from database
    public function getOrders() {
        $sql = "SELECT * FROM orderitem";
        return $this->conn->query($sql);
    }

    // Insert new order into database
    public function addOrder($item_name, $quantity, $price) {
        $sql = "INSERT INTO orderitem (item_name, quantity, price)
                VALUES ('$item_name', '$quantity', '$price')";
        return $this->conn->query($sql);
    }

    // Delete order by ID
    public function deleteOrder($id) {
        $sql = "DELETE FROM orderitem WHERE id = $id";
        return $this->conn->query($sql);
    }

    // Get single order by ID
    public function getOrderById($id) {
        $sql = "SELECT * FROM orderitem WHERE id = $id";
        return $this->conn->query($sql);
    }

    // Update existing order
    public function updateOrder($id, $item_name, $quantity, $price) {
        $sql = "UPDATE orderitem
                SET item_name = '$item_name',
                    quantity = '$quantity',
                    price = '$price'
                WHERE id = $id";

        return $this->conn->query($sql);
    }
}
?>