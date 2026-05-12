```php id="simplecommentdelete"
<?php

// Include database connection
include "db.php";

// Variable to store success/error message
$message = "";

// Check if ID is received from URL
if (isset($_GET['id']) && $_GET['id'] != "") {

    // Convert ID into integer for safety
    $id = intval($_GET['id']);

    // SQL query to delete record
    $sql = "DELETE FROM orderitem WHERE id=$id";

    // Execute delete query
    if ($conn->query($sql)) {

        // Success message
        $message = "Record deleted successfully";

    } else {

        // Error message
        $message = "Error deleting record: " . $conn->error;
    }

} else {

    // Message if ID is missing
    $message = "No ID received in URL";
}
?>

<!DOCTYPE html>
<html>

<head>

    <!-- Page title -->
    <title>SmartMeal</title>

    <!-- Connect CSS file -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- Main heading -->
<h1>SmartMeal System</h1>

<!-- Display success or error message -->
<p><?php echo $message; ?></p>

<!-- Link to go back -->
<a href="view.php">Go Back</a>

</body>
</html>
```
