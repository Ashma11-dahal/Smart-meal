<?php

/*
    Main order item management page
    -------------------------------
    This page lets the user add new order items, search existing items,
    and view all records in one table. It is useful as the main CRUD page
    for the Smart Meal order item management system.
*/

// Load the database connection so this page can read and write MySQL records.
require_once __DIR__ . "/../config/db.php";

// Load the model class that contains all order item database functions.
require_once __DIR__ . "/../models/orderModel.php";

// Load shared validation helpers so add/update rules stay consistent.
require_once __DIR__ . "/../config/validation.php";

// Create the model object and pass the active database connection to it.
$model = new OrderModel($conn);

// This variable stores success or error messages shown at the top of the page.
$message = "";

// These values must match the ENUM values in the database status column.
// Keeping them in one array makes it easy to reuse them in forms and validation.
$allowedStatuses = ["Pending", "Processed", "Cancelled"];

// Read the search keyword from the URL. If there is no keyword, use an empty string.
$keyword = trim($_GET["keyword"] ?? "");

// Read the selected status filter from the URL. The filter is optional.
$statusFilter = trim($_GET["status_filter"] ?? "");

// If someone changes the URL manually to an invalid status, ignore it safely.
if ($statusFilter !== "" && !in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = "";
}

/*
    Add form processing
    -------------------
    This block runs only after the user submits the Add Order Item form.
    It reads form values, validates them, and inserts the record if valid.
*/
if (isset($_POST["add_order"])) {
    $itemName = trim($_POST["item_name"] ?? "");
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);

    // Read the DATE and ENUM values from the add form.
    $orderDate = trim($_POST["order_date"] ?? "");
    $status = trim($_POST["status"] ?? "");

    // Validate all form fields before saving them into MySQL.
    $errors = validateOrderInput($itemName, $quantity, $price, $orderDate, $status);

    // If validation returns errors, show those errors and do not insert into database.
    if (!empty($errors)) {
        $message = getFirstValidationMessage($errors);
    } elseif ($model->addOrder($itemName, $quantity, $price, $orderDate, $status)) {
        // Redirect after successful insert to prevent duplicate form submission on refresh.
        header("Location: index.php?message=added");
        exit();
    } else {
        $message = "Error adding order item: " . $conn->error;
    }
}

// Show success message after redirecting from a successful insert.
if (isset($_GET["message"]) && $_GET["message"] === "added") {
    $message = "Order item added successfully.";
}

// If search or filter values exist, show matching records.
// Otherwise show every order item from the database.
if ($keyword !== "" || $statusFilter !== "") {
    $result = $model->searchOrder($keyword, $statusFilter);
} else {
    $result = $model->getOrders();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Meal Order Item Management</title>

    <!-- Link CSS file from assets folder. -->
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<header class="dashboard-navbar">
    <div class="nav-brand">
        <strong>Smart Meal</strong>
        <span>Order Dashboard</span>
    </div>

    <nav>
        <a class="active" href="/smart-meal/public/index.php">Dashboard</a>
        <a href="/smart-meal/views/add.php">Add</a>
        <a href="/smart-meal/views/view.php">View</a>
        <a href="/smart-meal/views/search.php">Search</a>
        <a href="/smart-meal/views/menu.php">Menu</a>
        <a href="/smart-meal/views/policy.php">Policy</a>
    </nav>
</header>

<main class="dashboard-main">
<h1>Smart Meal Order Item Management</h1>

<!-- Top navigation links for moving between website, dashboard, menu, and policy pages. -->
<p class="actions">
    <a href="/smart-meal/views/dashboard.php">Main Dashboard</a>
    <a href="/smart-meal/views/add.php">Add Order Item</a>
    <a href="/smart-meal/views/view.php">Edit or Delete Items</a>
    <a href="/smart-meal/views/menu.php">Website Menu</a>
    <a href="/smart-meal/views/policy.php">Policy</a>
</p>

<!-- Show a success or validation/database error message when needed. -->
<?php if ($message !== ""): ?>
    <p class="message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<!-- Add Order Item Section: this form creates a new order item record. -->
<section class="page-section">
    <h2>Add Order Item</h2>

    <form method="POST" action="index.php">
        <label>Item Name</label>
        <input type="text" name="item_name" minlength="2" maxlength="100" required>

        <label>Quantity</label>
        <input type="number" name="quantity" min="1" required>

        <label>Price</label>
        <input type="number" name="price" min="0.01" max="9999.99" step="0.01" required>

        <label>Order Date</label>
        <input type="date" name="order_date" required>

        <label>Status</label>
        <select name="status" required>
            <option value="">Select status</option>
            <option value="Pending">Pending</option>
            <option value="Processed">Processed</option>
            <option value="Cancelled">Cancelled</option>
        </select>

        <input type="submit" name="add_order" value="Add Order Item">
    </form>
</section>

<!-- Search and Filter Order Item Section: this form filters the table by item name and status. -->
<section class="page-section">
    <h2>Search and Filter Order Item</h2>

    <form method="GET" action="index.php">
        <label>Search</label>
        <input
            type="text"
            name="keyword"
            placeholder="Enter item name"
            value="<?php echo htmlspecialchars($keyword); ?>"
        >

        <label>Status Filter</label>
        <select name="status_filter">
            <option value="">All statuses</option>
            <!-- Print one dropdown option for each allowed status value. -->
            <?php foreach ($allowedStatuses as $statusOption): ?>
                <option value="<?php echo htmlspecialchars($statusOption); ?>" <?php echo $statusFilter === $statusOption ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($statusOption); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Apply Filter">
    </form>

    <?php if ($keyword !== "" || $statusFilter !== ""): ?>
        <!-- Selected filter chips show the user which filters are active. -->
        <div class="filter-chips">
            <?php if ($keyword !== ""): ?>
                <span class="filter-chip">Search: <?php echo htmlspecialchars($keyword); ?></span>
            <?php endif; ?>

            <?php if ($statusFilter !== ""): ?>
                <span class="filter-chip">Status: <?php echo htmlspecialchars($statusFilter); ?></span>
            <?php endif; ?>

            <a class="clear-filter" href="index.php">Clear filters</a>
        </div>
    <?php endif; ?>

    <p class="actions">
        <a href="index.php">Show All Records</a>
        <a href="../views/add.php">Open Add Page</a>
        <a href="../views/view.php">Open View Page</a>
        <a href="../views/search.php">Open Search Page</a>
    </p>
</section>

<!-- View Order Item Section: this table displays existing database records. -->
<section class="page-section">
    <h2>Order Item List</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Subtotal</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Escape output with htmlspecialchars to avoid printing unsafe HTML. -->
                <tr>
                    <td><?php echo htmlspecialchars($row["id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                    <td><?php echo htmlspecialchars($row["price"]); ?></td>
                    <td><?php echo htmlspecialchars($row["order_date"]); ?></td>
                    <td><?php echo htmlspecialchars($row["status"]); ?></td>
                    <td><?php echo htmlspecialchars($row["quantity"] * $row["price"]); ?></td>
                    <td>
                        <a href="../views/edit.php?id=<?php echo urlencode($row["id"]); ?>">Edit</a>
                    </td>
                    <td>
                        <a class="delete-link" href="../views/delete.php?id=<?php echo urlencode($row["id"]); ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">No records found</td>
            </tr>
        <?php endif; ?>
    </table>
</section>

</main>
</body>
</html>
