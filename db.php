<?php

/*
    Database configuration file
    ---------------------------
    This file creates the MySQL connection used by the whole Smart Meal project.
    Every model or page that needs database access includes this file.
*/

// Database host used by XAMPP. In a local XAMPP project this is normally localhost.
$servername = "localhost";

// Default XAMPP MySQL username. XAMPP usually uses root for local development.
$username = "root";

// Default XAMPP MySQL password is empty unless you changed it in phpMyAdmin/MySQL.
$password = "";

// Name of the database that stores order items and menu items for this project.
$database = "orderitem_management";

// Create the MySQL database connection using the mysqli extension.
$conn = new mysqli($servername, $username, $password, $database);

// Stop the page immediately if the database connection fails.
// This helps show a clear error instead of letting the rest of the page break.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use UTF-8 so names, categories, and special characters display correctly.
$conn->set_charset("utf8mb4");

?>
