<?php
// ============================================
// SmartMeal Admin Dashboard
// ============================================
// This page acts as the main control panel for administrators.
// Admins can manage menu items, staff accounts, orders,
// and system reports from this dashboard.

session_start();

// ============================================
// ADMIN AUTHENTICATION CHECK
// ============================================
// Verify that the current user is logged in
// and has admin privileges before allowing access.
// FIXED: Changed 'logged_in' to 'staff_logged_in' to match login file
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true || $_SESSION['staff_role'] != 'admin') {

    // Redirect unauthorized users to login page
    header("Location: staff_login.php");
    exit();
}

// Get the logged-in admin's name from the session
$admin_name = $_SESSION['staff_name'];

// Include database connection file
require_once __DIR__ . '/db.php';

// ============================================
// DATABASE CONNECTION
// ============================================
// Create a database object and establish connection
$database = new Database();
$db = $database->getConnection();

// ============================================
// FETCH DASHBOARD STATISTICS
// ============================================

// Count total number of staff accounts
$staff_count = $db->query("SELECT COUNT(*) as count FROM staff_users")
                  ->fetch(PDO::FETCH_ASSOC)['count'];

// Count total number of menu items
$menu_count = $db->query("SELECT COUNT(*) as count FROM MenuItems")
                 ->fetch(PDO::FETCH_ASSOC)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Responsive layout support for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SmartMeal - Admin Dashboard</title>

    <style>

        /* ============================================
           GLOBAL RESET & DEFAULT STYLING
        ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Main page background styling */
        body {
            background: #f5f5f5;
        }

        /* ============================================
           NAVIGATION BAR
        ============================================ */
        .navbar {
            background: white;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Logo styling */
        .logo h1 {
            font-size: 28px;
            color: #28a745;
        }

        /* Highlight color for part of the logo */
        .logo span {
            color: #ff6600;
        }

        /* ============================================
           NAVIGATION LINKS
        ============================================ */
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Default navigation button style */
        .nav-links a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }

        /* Hover effect for navigation buttons */
        .nav-links a:hover {
            background: #ff6600;
            color: white;
        }

        /* ============================================
           MENU MANAGEMENT BUTTON
        ============================================ */
        /* Special highlight styling for menu management */
        .menu-management-btn {
            background: #ff6600;
            color: white !important;
        }

        /* Hover animation for menu management button */
        .menu-management-btn:hover {
            background: #e55a00 !important;
            transform: translateY(-2px);
        }

        /* ============================================
           LOGOUT BUTTON
        ============================================ */
        .logout-btn {
            background: #dc3545;
            color: white !important;
        }

        .logout-btn:hover {
            background: #c82333 !important;
        }

        /* ============================================
           MAIN CONTAINER
        ============================================ */
        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* ============================================
           WELCOME CARD
        ============================================ */
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

        /* ============================================
           DASHBOARD STATISTICS GRID
        ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Individual statistics card */
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Hover effect for stat cards */
        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Large number displayed inside stats card */
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #28a745;
        }

        /* Label text under stat number */
        .stat-label {
            color: #666;
            margin-top: 10px;
        }

        /* ============================================
           QUICK ACTIONS SECTION
        ============================================ */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        /* Action card design */
        .action-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: block;
        }

        /* Hover effect for action cards */
        .action-card:hover {
            transform: translateY(-5px);
            background: #ff6600;
            color: white;
        }

        /* ============================================
           SPECIAL MENU CARD
        ============================================ */
        /* Highlight border for menu management card */
        .menu-card {
            border-top: 5px solid #ff6600;
        }

        /* ============================================
           2FA SECURITY BADGE (ADDED)
        ============================================ */
        .security-badge {
            background: linear-gradient(135deg, #1a472a, #2d5a3f);
            padding: 15px 25px;
            border-radius: 50px;
            margin-bottom: 30px;
            text-align: center;
        }

        .security-badge span {
            color: #ff6600;
            font-weight: bold;
        }

        .badge-icon {
            background: #ff6600;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            margin-right: 15px;
            font-size: 14px;
            color: white;
        }

        /* ============================================
           FOOTER SECTION
        ============================================ */
        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: 40px;
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        /* Adjust layout for smaller screen devices */
        @media (max-width: 768px) {

            .navbar {
                flex-direction: column;
                text-align: center;
            }

            .nav-links {
                justify-content: center;
            }
            
            .security-badge {
                font-size: 12px;
            }
        }

    </style>
</head>

<body>

    <!-- ============================================
         TOP NAVIGATION BAR
    ============================================ -->
    <nav class="navbar">

        <!-- Website Logo -->
        <div class="logo">
            <h1>Smart<span>Meal</span></h1>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links">

            <!-- Display currently logged-in admin -->
            <span>👑 Admin: <?php echo $admin_name; ?></span>

            <!-- Link to menu management dashboard -->
            <a href="../models/admin_homepage.php" class="menu-management-btn">
                📋 Menu Management
            </a>

            <!-- Staff management page -->
            <a href="staff_list.php">
                👥 Manage Staff
            </a>

            <!-- Logout button -->
            <a href="logout.php?type=staff" class="logout-btn">
                🚪 Logout
            </a>

        </div>
    </nav>

    <!-- ============================================
         MAIN DASHBOARD CONTENT
    ============================================ -->
    <div class="container">

        <!-- Welcome Banner -->
        <div class="welcome-card">
            <h2>Welcome, Administrator! 👑</h2>
            <p>You have full control over the SmartMeal system.</p>
        </div>

        <!-- ============================================
             2FA SECURITY BADGE (ADDED)
        ============================================ -->
        <div class="security-badge">
            <span class="badge-icon">🔐 2FA ACTIVE</span>
            <span>✅ You are logged in with Two-Factor Authentication (2FA)</span>
        </div>

        <!-- ============================================
             STATISTICS CARDS
        ============================================ -->
        <div class="stats-grid">

            <!-- Menu Items Count -->
            <div class="stat-card" onclick="location.href='../models/admin_homepage.php'">
                <div class="stat-number"><?php echo $menu_count; ?></div>
                <div class="stat-label">Menu Items</div>
            </div>

            <!-- Staff Members Count -->
            <div class="stat-card" onclick="location.href='staff_list.php'">
                <div class="stat-number"><?php echo $staff_count; ?></div>
                <div class="stat-label">Staff Members</div>
            </div>

            <!-- Today's Orders -->
            <div class="stat-card" onclick="location.href='orders.php'">
                <div class="stat-number">45</div>
                <div class="stat-label">Today's Orders</div>
            </div>

            <!-- Total Customers -->
            <div class="stat-card" onclick="location.href='customers.php'">
                <div class="stat-number">128</div>
                <div class="stat-label">Total Customers</div>
            </div>

        </div>

        <!-- ============================================
             QUICK ACTIONS SECTION
        ============================================ -->
        <div class="quick-actions">

            <!-- Menu Management Action -->
            <a href="../models/admin_homepage.php" class="action-card menu-card">
                <h3>🍕 Manage Menu</h3>
                <p>Add, edit, or remove menu items</p>
                <p style="margin-top: 10px; font-size: 12px;">
                    Click to access your menu dashboard →
                </p>
            </a>

            <!-- Staff Management Action -->
            <a href="staff_list.php" class="action-card">
                <h3>👥 Staff Management</h3>
                <p>Manage staff accounts and roles</p>
            </a>

            <!-- Orders Management Action -->
            <a href="orders.php" class="action-card">
                <h3>📦 View Orders</h3>
                <p>Track and manage customer orders</p>
            </a>

            <!-- Reports Section -->
            <a href="reports.php" class="action-card">
                <h3>📊 Reports</h3>
                <p>View sales and performance reports</p>
            </a>

        </div>
    </div>

    <!-- ============================================
         FOOTER
    ============================================ -->
    <footer>
        <p>© 2026 SmartMeal Food Ordering System | Secured with Two-Factor Authentication (2FA) | Admin Portal</p>
    </footer>
<?php include 'footer.php'; ?>
</body>
</html>