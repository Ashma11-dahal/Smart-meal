<?php
session_start();
include "db.php";

/*
=====================================
GET LOGIN FORM DATA
=====================================
*/

$email = $_POST['email'];
$password = $_POST['password'];

/*
=====================================
CHECK USER FROM DATABASE
=====================================
*/

$stmt = $conn->prepare(
    "SELECT * FROM users WHERE email = ?"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

/*
=====================================
VALIDATE LOGIN
=====================================
*/

if ($user && password_verify($password, $user['password'])) {

    /*
    =================================
    CHECK ROLE
    =================================
    */

    if (
        $user['role'] == 'admin' ||
        $user['role'] == 'staff'
    ) {

        /*
        =============================
        CREATE SESSION
        =============================
        */

        $_SESSION['staff'] = $user;

        /*
        =============================
        REDIRECT TO DASHBOARD
        =============================
        */

        header("Location: admin_dashboard.php");

        exit();

    } else {

        echo "Unauthorized Access";
    }

} else {

    echo "Invalid Email or Password";
}
?>