<?php

/*
    Order controller
    ----------------
    This controller prepares common order data for view pages.
    Pages like view.php include this file so they can use the $result variable
    without repeating the same database loading code.
*/

// Load database connection so the model can communicate with MySQL.
require_once __DIR__ . "/../config/db.php";

// Load the OrderModel class, which contains all database query functions.
require_once __DIR__ . "/../models/orderModel.php";

// Create the model object used by view pages.
$model = new OrderModel($conn);

// Fetch all order items for pages that need the full list of records.
// The view page loops through this result and prints each row inside a table.
$result = $model->getOrders();

?>
