<?php
// Load database connection and model
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/orderModel.php";

// Fetch menu items from database
$model = new OrderModel($conn);
$result = $model->getAvailableMenuItems();

// Track category grouping
$currentCategory = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SmartMeal Menu</title>

    <link rel="stylesheet" href="../assets/style.css">

    <!-- Simple styling for static cards -->
    <style>
        body {
            font-family: Arial;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions a {
            margin: 0 10px;
            text-decoration: none;
            color: #f97316;
            font-weight: bold;
        }

        .menu-section {
            padding: 20px;
        }

        .menu-category {
            margin-top: 30px;
            color: #333;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 10px;
        }

        .menu-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .menu-card img {
            width: 100%;
            border-radius: 12px;
        }

        .price {
            color: #f97316;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>SmartMeal Menu</h1>

<!-- Navigation -->
<p class="actions">
    <a href="/smart-meal/public/index.php">Dashboard</a>
    <a href="/smart-meal/views/view.php">Orders</a>
    <a href="/smart-meal/views/policy.php">Policy</a>
</p>

<!-- ================= DATABASE MENU ================= -->
<section class="menu-section">

<?php if ($result && $result->num_rows > 0): ?>

    <?php while ($row = $result->fetch_assoc()): ?>

        <?php if ($currentCategory !== $row["category"]): ?>
            <?php $currentCategory = $row["category"]; ?>
            <h2 class="menu-category">
                <?php echo htmlspecialchars($currentCategory); ?>
            </h2>
        <?php endif; ?>

        <div class="menu-grid">

            <div class="menu-card">
                <h3><?php echo htmlspecialchars($row["name"]); ?></h3>
                <p><?php echo htmlspecialchars($row["category"]); ?></p>
                <strong class="price">
                    kr <?php echo number_format((float)$row["price"], 2); ?>
                </strong>
            </div>

        </div>

    <?php endwhile; ?>

<?php else: ?>
    <p style="text-align:center;">
        No menu items available yet.
    </p>
<?php endif; ?>

</section>

<!-- ================= STATIC MENU (CHICKEN, SAMOSA, WINGS) ================= -->
<h1>Our Popular Items</h1>

<div class="menu-grid" style="padding:40px;">

    <!-- Chicken -->
    <div class="menu-card">
        <img src="https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=400">
        <h3>Chicken</h3>
        <p>Delicious grilled chicken</p>
        <div class="price">80 kr</div>
    </div>

    <!-- Samosa -->
    <div class="menu-card">
        <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400">
        <h3>Samosa</h3>
        <p>Crispy spicy samosas</p>
        <div class="price">40 kr</div>
    </div>

   <!-- Wings -->
<div class="menu-card">
    <img src="https://images.unsplash.com/photo-1527477396000-e27163b481c2?auto=format&fit=crop&w=400&q=80" alt="Chicken Wings">
    <h3>Chicken Wings</h3>
    <p>Spicy crispy wings</p>
    <div class="price">100 kr</div>

</div>

</body>
</html>