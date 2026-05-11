<?php
require_once __DIR__ . "/../config/db.php";

class OrderModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // VIEW ALL ORDERS
    public function getOrders() {
        $sql = "SELECT * FROM orderitem";
        return $this->conn->query($sql);
    }

    // ADD ORDER
    public function addOrder($item_name, $quantity, $price) {
        $sql = "INSERT INTO orderitem (item_name, quantity, price)
                VALUES ('$item_name', '$quantity', '$price')";
        return $this->conn->query($sql);
    }

    // DELETE ORDER
    public function deleteOrder($id) {
        $sql = "DELETE FROM orderitem WHERE id = $id";
        return $this->conn->query($sql);
    }

    // GET SINGLE ORDER
    public function getOrderById($id) {
        $sql = "SELECT * FROM orderitem WHERE id = $id";
        return $this->conn->query($sql);
    }

    // UPDATE ORDER
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