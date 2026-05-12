```php id="commenteditphp"
<?php

// Include database connection file
include "db.php";


// Check if ID is received from URL
if (isset($_GET['id']) && $_GET['id'] != "") {

    // Convert ID into integer for safety
    $id = intval($_GET['id']);

    // SQL query to get selected record
    $sql = "SELECT * FROM orderitem WHERE id=$id";

    // Execute query
    $result = $conn->query($sql);

    // Check if record exists
    if ($result->num_rows > 0) {

        // Fetch record data
        $row = $result->fetch_assoc();

    } else {

        // Message if record not found
        echo "Record not found";
        exit();
    }

} else {

    // Message if ID is missing
    echo "No ID provided";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

    <!-- Page title -->
    <title>SmartMeal Edit</title>

    <!-- Connect CSS file -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- Main heading -->
<h1>SmartMeal System</h1>

<!-- Sub heading -->
<h2>Edit Order Item</h2>

<!-- Edit form starts -->
<form method="POST" action="update.php">

    <!-- Hidden field to store record ID -->
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <!-- Item name input -->
    Item Name:
    <input type="text" name="item_name"
    value="<?php echo $row['item_name']; ?>"><br><br>

    <!-- Quantity input -->
    Quantity:
    <input type="number" name="quantity"
    value="<?php echo $row['quantity']; ?>"><br><br>

    <!-- Price input -->
    Price:
    <input type="text" name="price"
    value="<?php echo $row['price']; ?>"><br><br>

    <!-- Update button -->
    <input type="submit" name="update" value="Update Order">

</form>
<!-- Edit form ends -->

</body>
</html>
```
