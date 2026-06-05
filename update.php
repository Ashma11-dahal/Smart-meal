<?php

/*
    Update order item process page
    ------------------------------
    This page does not display a normal form. It receives data from edit.php,
    validates the submitted values, updates the database, and redirects back
    to view.php when the update succeeds.
*/

// Load database, model, and validation helpers.
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/orderModel.php";
require_once __DIR__ . "/../config/validation.php";

// Create model object for the updateOrder() database operation.
$model = new OrderModel($conn);

// Process update only when the edit form is submitted with the update button.
if (isset($_POST["update"])) {
    // Read submitted values and validate numeric fields using filter_input.
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $itemName = trim($_POST["item_name"] ?? "");
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);

    // Receive the DATE and ENUM values from edit.php.
    $orderDate = trim($_POST["order_date"] ?? "");
    $status = trim($_POST["status"] ?? "");

    // Validate the submitted edit form before updating the database.
    $errors = validateOrderInput($itemName, $quantity, $price, $orderDate, $status);

    // Validate input before updating the database.
    // If the ID is invalid or validation fails, show a friendly message page.
    if (!$id || !empty($errors)) {
        $message = !$id ? "No valid order ID received." : getFirstValidationMessage($errors);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Update Order</title>
            <link rel="stylesheet" href="../assets/style.css">
        </head>
        <body>
            <h1>SmartMeal System</h1>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <p class="actions"><a href="view.php">Back to Orders</a></p>
        </body>
        </html>
        <?php
        exit();
    }

    // If validation passed, update the selected order item in the database.
    if ($model->updateOrder($id, $itemName, $quantity, $price, $orderDate, $status)) {
        // Redirect back to the list so the user can see the updated record.
        header("Location: view.php");
        exit();
    }

    // Show database error only if the update query failed.
    die("Update failed: " . $conn->error);
}

// If this page is opened directly without submitting the edit form, return to the list.
header("Location: view.php");
exit();

?>
