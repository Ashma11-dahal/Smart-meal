<?php

// Include database connection file
include "../config/db.php";

// Check if form is submitted
if(isset($_POST['submit'])){

    // STEP 1: Get form data
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // STEP 2: VALIDATION (VERY IMPORTANT)
    if(empty($item_name) || empty($quantity) || empty($price)){
        echo "All fields are required!";
        exit();
    }

    if(empty($item_name)){
        echo "Item name is required!";
        exit();
    }

    if(!is_numeric($quantity) || $quantity <= 0){
        echo "Quantity must be a positive number!";
        exit();
    }

    if(!is_numeric($price) || $price <= 0){
        echo "Price must be a valid number!";
        exit();
    }

    // STEP 3: INSERT INTO DATABASE
    $sql = "INSERT INTO orderitem(item_name, quantity, price)
            VALUES('$item_name', '$quantity', '$price')";

    if($conn->query($sql)){
        echo "Record added successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Order</title>

    <!-- CSS file -->
    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<h1>SmartMeal Order Management</h1>
<h2>Add New Order</h2>

<form method="POST">

    Item Name:
    <input type="text" name="item_name"><br><br>

    Quantity:
    <input type="number" name="quantity"><br><br>

    Price:
    <input type="text" name="price"><br><br>

    <input type="submit" name="submit" value="Add Order">

</form>

</body>
</html>