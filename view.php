<?php
require_once __DIR__ . "/../controllers/orderController.php";
?>

<!DOCTYPE html>
<html>
<head>

<title>View Orders</title>

<link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<h1>View Orders</h1>

<a href="add.php">Add Order</a>

<br><br>

<table border="1">

<tr>
    <th>ID</th>
    <th>Item Name</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php if ($result && $result->num_rows > 0) { ?>

    <?php while($row = $result->fetch_assoc()){ ?>

    <tr>

        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['item_name']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['price']; ?></td>

        <td>
            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
        </td>

        <td>
            <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
        </td>

    </tr>

    <?php } ?>

<?php } else { ?>

    <tr>
        <td colspan="6">No records found</td>
    </tr>

<?php } ?>

</table>

</body>
</html>