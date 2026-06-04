<?php

/*
    Add order item page
    -------------------
    This page contains a separate form for adding one new order item.
    It uses the same model and validation helpers as the main management page.
*/

// Load database connection file so the page can insert records.
require_once __DIR__ . "/../config/db.php";

// Load order model file, which contains the addOrder() function.
require_once __DIR__ . "/../models/orderModel.php";

// Load validation helpers to check item name, quantity, and price before saving.
require_once __DIR__ . "/../config/validation.php";

// Create object of OrderModel class and pass the database connection.
$model = new OrderModel($conn);

// Store success or error message for display on the page.
$message = "";

// Check if the form is submitted using the submit button named "submit".
if (isset($_POST["submit"])) {

    // Read and sanitize form values from the POST request.
    $itemName = trim($_POST["item_name"] ?? "");
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);

    // New fields added to the orderitem table.
    // order_date is saved as a DATE and status is saved as an ENUM value.
    $orderDate = trim($_POST["order_date"] ?? "");
    $status = trim($_POST["status"] ?? "");

    // Validate all fields before sending them to the model/database.
    $errors = validateOrderInput($itemName, $quantity, $price, $orderDate, $status);

    // Validate form input. If errors exist, do not add the record.
    if (!empty($errors)) {

        $message = getFirstValidationMessage($errors);

    } else {

        // Add order item using the model function after validation passes.
        if ($model->addOrder($itemName, $quantity, $price, $orderDate, $status)) {

            // Redirect to view page after successful insert so the user can see the new record.
            header("Location: view.php");
            exit();

        } else {

            // Show database error if insert fails.
            $message = "Error adding record: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Order Item</title>

    <!-- Link CSS file -->
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<h1>SmartMeal Order Management</h1>
<h2>Add New Order Item</h2>

<!-- Show validation or database message only when there is something to show. -->
<?php if ($message !== ""): ?>
    <p class="message">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<!-- Add Order Item Form: required/min/max attributes provide browser-side validation. -->
<form method="POST">

    <label>Item Name</label>
    <input type="text" name="item_name" minlength="2" maxlength="100" required>

    <label>Quantity</label>
    <input type="number" name="quantity" min="1" required>

    <label>Price</label>
    <input type="number" name="price" min="0.01" max="9999.99" step="0.01" required>

    <label>Order Date</label>
    <input type="date" name="order_date" required>

    <!-- Status must match one of the ENUM values in the MySQL table. -->
    <label>Status</label>
    <select name="status" required>
        <option value="">Select status</option>
        <option value="Pending">Pending</option>
        <option value="Processed">Processed</option>
        <option value="Cancelled">Cancelled</option>
    </select>

    <input type="submit" name="submit" value="Add Order Item">

</form>

<p class="actions">
    <a href="view.php">Back to Orders</a>
    <a href="/smart-meal/views/menu.php">Website Menu</a>
    <a href="/smart-meal/views/policy.php">Policy</a>
</p>

</body>
</html>
