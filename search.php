<?php

/*
    Search orders page
    ------------------
    This page lets the user search/filter order items by item name.
    The matching records are displayed in a table under the search form.
*/

// Load database and model so this page can run the search query.
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/orderModel.php";

// Create model object and set default search values.
$model = new OrderModel($conn);
$result = null;
$keyword = "";
$statusFilter = "";

// These status options match the ENUM values in the orderitem database table.
$allowedStatuses = ["Pending", "Processed", "Cancelled"];

// Search only when the form is submitted. Before that, the table shows no records.
if (isset($_POST["search"])) {
    $keyword = trim($_POST["keyword"] ?? "");

    // Read the optional status filter from the dropdown.
    $statusFilter = trim($_POST["status_filter"] ?? "");

    // Ignore invalid status values if someone changes the HTML or request manually.
    if ($statusFilter !== "" && !in_array($statusFilter, $allowedStatuses, true)) {
        $statusFilter = "";
    }

    // Search by item name and filter by status when a status is selected.
    $result = $model->searchOrder($keyword, $statusFilter);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Orders</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<h1>Search Orders</h1>

<!-- Search form. The keyword is kept in the input after submit for better usability. -->
<form method="POST">
    <label>Search</label>
    <input type="text" name="keyword" placeholder="Enter item name" value="<?php echo htmlspecialchars($keyword); ?>">

    <label>Status Filter</label>
    <select name="status_filter">
        <option value="">All statuses</option>
        <!-- Build the status dropdown from the allowed ENUM values. -->
        <?php foreach ($allowedStatuses as $statusOption): ?>
            <option value="<?php echo htmlspecialchars($statusOption); ?>" <?php echo $statusFilter === $statusOption ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($statusOption); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" name="search" value="Search">
</form>

<?php if ($keyword !== "" || $statusFilter !== ""): ?>
    <!-- Chips make the active search/filter values visible under the form. -->
    <div class="filter-chips">
        <?php if ($keyword !== ""): ?>
            <span class="filter-chip">Search: <?php echo htmlspecialchars($keyword); ?></span>
        <?php endif; ?>

        <?php if ($statusFilter !== ""): ?>
            <span class="filter-chip">Status: <?php echo htmlspecialchars($statusFilter); ?></span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<p class="actions">
    <a href="view.php">Back to Orders</a>
    <a href="/smart-meal/views/menu.php">Website Menu</a>
    <a href="/smart-meal/views/policy.php">Policy</a>
</p>

<!-- Search result table. It displays matching order items only. -->
<table>
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Order Date</th>
        <th>Status</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["id"]); ?></td>
                <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                <td><?php echo htmlspecialchars($row["price"]); ?></td>
                <td><?php echo htmlspecialchars($row["order_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["status"]); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No records found</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
