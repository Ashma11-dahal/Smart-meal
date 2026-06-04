<?php
// /FoodOrderingSystem/views/logout.php
session_start();
// Determine logout type (staff, customer, or all)

$type = isset($_GET['type']) ? $_GET['type'] : 'all';
// Clear session data based on logout type
if ($type == 'staff') {
    unset($_SESSION['staff_logged_in']);
    unset($_SESSION['staff_id']);
    unset($_SESSION['staff_name']);
    unset($_SESSION['staff_email']);
    unset($_SESSION['staff_role']);
    header("Location: staff_login.php");
    // Clear all session data for staff logout
} elseif ($type == 'customer') {
    unset($_SESSION['customer_logged_in']);
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    header("Location: customer_login.php");
        // Clear all session data for customer logout
} else {
    session_destroy();
    header("Location: index.php");
}
exit();
?>