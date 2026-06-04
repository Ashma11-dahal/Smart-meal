<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare(
    "SELECT * FROM customers WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['customer'] = $user;

    header("Location: customer_dashboard.php");

} else {

    echo "Invalid Customer Login";
}
?>