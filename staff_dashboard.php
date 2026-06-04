<?php
session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header("Location: staff_login.php");
    exit();

}
// Include database connection
require_once __DIR__ . '/db.php';

$staff_name = $_SESSION['staff_name'];
$staff_role = $_SESSION['staff_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMeal - Staff Dashboard</title>
    // Styles for the staff dashboard
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .navbar {
            background: white;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo h1 {
            font-size: 28px;
            color: #28a745;
        }

        .logo span {
            color: #ff6600;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
     
        /* Styles for the welcome card and statistics*/
        .nav-links a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }
/* Styles for the welcome card and statistics */
        .nav-links a:hover {
            background: #ff6600;
            color: white;
        }
/* Styles for the logout button */
        .logout-btn {
            background: #dc3545;
            color: white !important;
        }
/* Styles for the welcome card and statistics */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #28a745, #ff6600);
            padding: 40px;
            border-radius: 20px;
            color: white;
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #28a745;
        }

        .stat-label {
            color: #666;
            margin-top: 10px;
        }
/* Styles for the menu management link and footer */
        .menu-link {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 10px;
            margin-top: 20px;
            transition: 0.3s;
        }

        .menu-link:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: 40px;
        }
/* Styles for role badge and staff info */
        .role-badge {
            background: #ff6600;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    // Navigation bar with logo and links
    <nav class="navbar">
        <div class="logo">
            <h1>Smart<span>Meal</span></h1>
        </div>
        <div class="nav-links">
            <span>Welcome, <?php echo $staff_name; ?></span>
            <span class="role-badge"><?php echo ucfirst($staff_role); ?></span>
            <a href="../menu_dashboard.php">📋 Menu Management</a>
            <a href="logout.php?type=staff" class="logout-btn">🚪 Logout</a>
        </div>
    </nav>
    // Main content area with welcome message and statistics
     <div class="container">
        <div class="welcome-card">
            <h2>Welcome, <?php echo $staff_name; ?>! 👋</h2>
            <p>You are logged in as a <strong><?php echo ucfirst($staff_role); ?></strong>. 
               <?php echo $staff_role == 'admin' ? 'You have full access to all features.' : 'You can manage menu items and view orders.'; ?>
            </p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">24</div>
                <div class="stat-label
/* Styles for the menu management link and footer */
    <div class="container">
        <div class="welcome-card">
            <h2>Welcome, <?php echo $staff_name; ?>! 👋</h2>
            <p>You are logged in as a <strong><?php echo ucfirst($staff_role); ?></strong>. 
               <?php echo $staff_role == 'admin' ? 'You have full access to all features.' : 'You can manage menu items and view orders.'; ?>
            </p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">24</div>
                <div class="stat-label">Today's Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Active Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$345</div>
                <div class="stat-label">Revenue Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">18</div>
                <div class="stat-label">Menu Items</div>
            </div>
        </div>
/* Styles for the menu management link and footer */
        <div style="text-align: center;">
            <a href="../menu_dashboard.php" class="menu-link">🍕 Manage Menu Items →</a>
        </div>
    </div>

    <footer>
        <p>© 2026 SmartMeal Food Ordering System | Staff Portal</p>
    </footer>
</body>
</html>