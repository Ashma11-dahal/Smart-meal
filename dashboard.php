<!DOCTYPE html>
<html>
<head>
    <title>Smart Meal Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<header class="dashboard-navbar">
    <div class="nav-brand">
        <strong>Smart Meal</strong>
        <span>Dashboard</span>
    </div>

    <nav>
        <a class="active" href="/smart-meal/views/dashboard.php">Dashboard</a>
        <a href="/smart-meal/public/index.php">Orders</a>
        <a href="/smart-meal/views/add.php">Add</a>
        <a href="/smart-meal/views/view.php">View</a>
        <a href="/smart-meal/views/search.php">Search</a>
        <a href="/smart-meal/views/menu.php">Menu</a>
        <a href="/smart-meal/views/policy.php">Policy</a>
    </nav>
</header>

<main class="dashboard-main">
<h1>Smart Meal Dashboard</h1>

<!--
    Dashboard cards
    ---------------
    These cards act as simple navigation shortcuts for the main project areas:
    order item management, website menu, and policy information.
-->
<div class="dashboard">
    <div class="card">
        <h2>Order Item Management</h2>
        <p>View, add, update, delete, and search order items.</p>
        <a href="/smart-meal/public/index.php">Manage Orders</a>
        <a href="/smart-meal/views/add.php">Add Item</a>
        <a href="/smart-meal/views/view.php">Edit or Delete</a>
    </div>
    <div class="card">
        <h2>Website Menu</h2>
        <p>Show available menu items on the website.</p>
        <a href="/smart-meal/views/menu.php">View Menu</a>
    </div>
    <div class="card">
        <h2>Policy</h2>
        <p>Read the SmartMeal privacy, order, payment, and security policy.</p>
        <a href="/smart-meal/views/policy.php">View Policy</a>
    </div>
</div>
</main>

</body>
</html>
