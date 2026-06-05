<?php

/*
    View orders page
    ----------------
    This page displays all order item records in a table.
    It also provides links to add, search, edit, and delete records.
*/

// Load controller, which gives this page the $result variable containing all orders.
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

<!-- Navigation links for common order management actions. -->
<p class="actions">
    <a href="../public/index.php">Dashboard</a>
    <a href="add.php">Add Order</a>
    <a href="search.php">Search Orders</a>
    <a href="/smart-meal/views/menu.php">Website Menu</a>
    <a href="/smart-meal/views/policy.php">Policy</a>
</p>

<!-- Order item table. Each database row is printed as one table row. -->
<table>
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Order Date</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <!-- htmlspecialchars protects the page from unsafe HTML stored in the database. -->
                <td><?php echo htmlspecialchars($row["id"]); ?></td>
                <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                <td><?php echo htmlspecialchars($row["price"]); ?></td>
                <td><?php echo htmlspecialchars($row["order_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["status"]); ?></td>
                <td><a href="edit.php?id=<?php echo urlencode($row["id"]); ?>">Edit</a></td>
                <td><a class="delete-link" href="delete.php?id=<?php echo urlencode($row["id"]); ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No records found</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
