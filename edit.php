<?php

/*
    Edit order item page
    --------------------
    This page loads one existing order item by ID and displays its current
    values inside a form. The form submits to update.php, where validation
    and the database update are completed.
*/

// Load database connection and order model files.
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/orderModel.php";

// Create a new instance of OrderModel and pass the active database connection.
$model = new OrderModel($conn);

// Validate and get the order ID from the URL using GET method.
// Example URL: edit.php?id=5
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

// Stop execution if the ID is invalid or missing because we do not know what to edit.
if (!$id) {
    die("No valid ID provided.");
}

// Retrieve the selected order record from the database so the form can be pre-filled.
$row = $model->getOrderById($id);

// Stop execution if no matching record is found for the requested ID.
if (!$row) {
    die("Record not found.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>

    <!-- Link external CSS stylesheet -->
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<!-- Page heading -->
<h1>SmartMeal System</h1>
<h2>Edit Order Item</h2>

<!-- Form to update an existing order. It sends data to update.php using POST. -->
<form method="POST" action="update.php">

    <!-- Hidden field stores order ID so update.php knows which record to update. -->
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row["id"]); ?>">

    <!-- Item name input. minlength and maxlength help validate in the browser. -->
    <label>Item Name</label>
    <input type="text" 
           name="item_name" 
           value="<?php echo htmlspecialchars($row["item_name"]); ?>" 
           minlength="2"
           maxlength="100"
           required>

    <!-- Quantity input. min="1" prevents zero or negative quantities in the browser. -->
    <label>Quantity</label>
    <input type="number" 
           name="quantity" 
           min="1" 
           value="<?php echo htmlspecialchars($row["quantity"]); ?>" 
           required>

    <!-- Price input. step="0.01" allows decimal prices such as 160.50. -->
    <label>Price</label>
    <input type="number" 
           name="price" 
           value="<?php echo htmlspecialchars($row["price"]); ?>" 
           min="0.01"
           max="9999.99"
           step="0.01"
           required>

    <label>Order Date</label>
    <input type="date"
           name="order_date"
           value="<?php echo htmlspecialchars($row["order_date"] ?? ""); ?>"
           required>

    <!-- Keep the saved ENUM status selected when the edit page opens. -->
    <label>Status</label>
    <select name="status" required>
        <option value="">Select status</option>
        <option value="Pending" <?php echo ($row["status"] ?? "") === "Pending" ? "selected" : ""; ?>>Pending</option>
        <option value="Processed" <?php echo ($row["status"] ?? "") === "Processed" ? "selected" : ""; ?>>Processed</option>
        <option value="Cancelled" <?php echo ($row["status"] ?? "") === "Cancelled" ? "selected" : ""; ?>>Cancelled</option>
    </select>

    <!-- Submit button sends the updated record to update.php. -->
    <input type="submit" name="update" value="Update Order">
</form>

<!-- Navigation link back to orders page -->
<p class="actions">
    <a href="view.php">Back to Orders</a>
    <a href="/smart-meal/views/menu.php">Website Menu</a>
    <a href="/smart-meal/views/policy.php">Policy</a>
</p>

</body>
</html>
