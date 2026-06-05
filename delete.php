<?php

/*
    Delete order item page
    ----------------------
    This page deletes one order item based on the ID in the URL.
    After deleting, it shows a message and a link back to the list.
*/

// Load database and model so this page can run the delete query.
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/orderModel.php";

// Create model object and prepare a message for the result.
$model = new OrderModel($conn);
$message = "";

// Validate and read the ID from the URL. Example: delete.php?id=5
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

// If the ID is valid, delete the record. Otherwise show an error message.
if ($id) {
    $message = $model->deleteOrder($id)
        ? "Record deleted successfully."
        : "Error deleting record: " . $conn->error;
} else {
    $message = "No valid ID received in URL.";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Order</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<h1>SmartMeal System</h1>

<!-- Show whether the delete operation succeeded or failed. -->
<p class="message"><?php echo htmlspecialchars($message); ?></p>

<!-- Link back to the order list after delete action. -->
<p class="actions">
    <a href="view.php">Go Back</a>
</p>

</body>
</html>
