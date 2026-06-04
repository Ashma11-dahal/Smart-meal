<?php

// ================================
// Smart Meal Admin Homepage
// ================================
// This page serves as the main dashboard for the admin panel.
// It provides quick navigation, stats overview, and access to menu management tools.

session_start();

// Optional authentication check (currently disabled for testing purposes)

// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: login.php");
//     exit();
// }

// File path for the Menu Dashboard page
// This is used in multiple links throughout the admin panel
$menu_dashboard = "menu dashboard.php"; // actual file name

// ================================
// Sample Dashboard Statistics
// ================================
// These values simulate menu item counts.

$stats = [
    'total_items' => 13,  // Total number of menu items available
    'pizza_items' => 3,   // Number of pizza items
    'burger_items' => 3,  // Number of burger items
    'drink_items' => 3,   // Number of drink items
    'snack_items' => 3    // Number of snack items
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Meal - Admin Homepage</title>

    <!-- ================================
         MAIN ADMIN STYLES
         This section defines the full UI design
         including navbar, cards, buttons, and footer
    ================================= -->
    <style>

        /* Reset default browser styling for consistency */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Overall page background and layout */
        body {
            background: #f5f7fb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ================= NAVBAR ================= */
        /* Top navigation bar styling */
        .navbar {
            background: linear-gradient(135deg, #1a472a, #2d5a3f);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Container for navbar content alignment */
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Logo section styling (icon + text) */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo span {
            font-size: 1.8rem;
        }

        .logo h1 {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .logo p {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        /* Navigation links container */
        .nav-links {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Individual navigation link styling */
        .nav-link {
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: 500;
        }

        /* Hover effect for navigation links */
        .nav-link:hover {
            background: #ff6b35;
            transform: translateY(-2px);
        }

        /* Admin identity badge on navbar */
        .admin-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ================= MAIN CONTAINER ================= */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
            flex: 1;
        }

        /* Welcome banner at top of dashboard */
        .welcome-card {
            background: linear-gradient(135deg, #ff6b35, #ff8c42);
            padding: 2rem;
            border-radius: 20px;
            color: white;
            margin-bottom: 2rem;
        }

        .welcome-card h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        /* ================= STATS GRID ================= */
        /* Grid layout for dashboard statistics cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Individual stat card design */
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.3s;
            cursor: pointer;
            border: 1px solid #e0e0e0;
        }

        /* Hover animation for stat cards */
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a472a;
        }

        .stat-label {
            color: #666;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        /* ================= QUICK ACTIONS ================= */
        .quick-actions {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            border: 1px solid #e0e0e0;
        }

        .quick-actions h3 {
            margin-bottom: 1rem;
            color: #1a472a;
        }

        /* Container for action buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Primary action button styling */
        .action-btn {
            padding: 0.8rem 1.5rem;
            background: #ff6b35;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.3s;
            font-weight: 500;
        }

        .action-btn:hover {
            background: #e55a2b;
            transform: translateY(-2px);
        }

        /* Secondary action button variant */
        .action-btn.secondary {
            background: #28a745;
        }

        .action-btn.secondary:hover {
            background: #218838;
        }

        /* ================= MAIN DASHBOARD BUTTON ================= */
        /* Large CTA button to open menu management dashboard */
        .dashboard-btn {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #1a472a, #2d5a3f);
            color: white;
            text-decoration: none;
            padding: 1.5rem;
            border-radius: 16px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: 0.3s;
            margin-bottom: 2rem;
        }

        .dashboard-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        /* ================= FOOTER SECTION ================= */
        .footer {
            background: #1a1a2e;
            color: #ccc;
            padding: 2rem 2rem 1rem;
            margin-top: 2rem;
        }

        /* Footer grid layout */
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        /* Footer section titles */
        .footer-section h4 {
            color: #ff6b35;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .footer-section a:hover {
            color: #ff6b35;
        }

        /* Copyright text styling */
        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #333;
            font-size: 0.8rem;
        }

        /* ================= RESPONSIVE DESIGN ================= */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                text-align: center;
            }
            .container {
                padding: 0 1rem;
            }
        }

    </style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <div class="nav-container">

        <!-- Logo Section -->
        <div class="logo">
            <span>🍽️</span>
            <div>
                <h1>Smart Meal</h1>
                <p>Admin Panel</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="admin_homepage.php" class="nav-link">🏠 Home</a>
            <a href="<?php echo $menu_dashboard; ?>" class="nav-link">📋 Menu Dashboard</a>
            <a href="#" class="nav-link">📊 Reports</a>
            <a href="#" class="nav-link">⚙️ Settings</a>
        </div>

        <!-- Admin Identity Badge -->
        <div class="admin-badge">
            <span>👨‍💼</span>
            <span>Admin</span>
        </div>

    </div>
</nav>

<!-- ================= MAIN CONTENT ================= -->
<div class="container">

    <!-- Welcome Message Card -->
    <div class="welcome-card">
        <h2>Welcome, Admin! 👋</h2>
        <p>Manage your menu items, track availability, and keep your Smart Meal system running smoothly.</p>
    </div>

    <!-- ================= STATS SECTION ================= -->
    <div class="stats-grid">

        <!-- Total Items -->
        <div class="stat-card" onclick="window.location.href='<?php echo $menu_dashboard; ?>'">
            <div class="stat-number"><?php echo $stats['total_items']; ?></div>
            <div class="stat-label">Total Menu Items</div>
        </div>

        <!-- Pizza Items -->
        <div class="stat-card" onclick="window.location.href='<?php echo $menu_dashboard; ?>?filter=pizza'">
            <div class="stat-number"><?php echo $stats['pizza_items']; ?></div>
            <div class="stat-label">Pizza Items</div>
        </div>

        <!-- Burger Items -->
        <div class="stat-card" onclick="window.location.href='<?php echo $menu_dashboard; ?>?filter=burger'">
            <div class="stat-number"><?php echo $stats['burger_items']; ?></div>
            <div class="stat-label">Burger Items</div>
        </div>

        <!-- Drinks Items -->
        <div class="stat-card" onclick="window.location.href='<?php echo $menu_dashboard; ?>?filter=drinks'">
            <div class="stat-number"><?php echo $stats['drink_items']; ?></div>
            <div class="stat-label">Drink Items</div>
        </div>

    </div>

    <!-- Main Navigation CTA -->
    <a href="<?php echo $menu_dashboard; ?>" class="dashboard-btn">
        🚀 Go to Menu Items Management Dashboard →
    </a>

    <!-- ================= QUICK ACTIONS ================= -->
    <div class="quick-actions">
        <h3>⚡ Quick Actions</h3>
        <div class="action-buttons">

            <a href="<?php echo $menu_dashboard; ?>?action=add" class="action-btn">
                ➕ Add New Menu Item
            </a>

            <a href="<?php echo $menu_dashboard; ?>?action=edit" class="action-btn secondary">
                ✏️ Edit Menu Items
            </a>

            <a href="<?php echo $menu_dashboard; ?>?action=list" class="action-btn">
                📋 View All Items
            </a>

        </div>
    </div>

</div>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <div class="footer-container">

        <!-- Policies Section -->
        <div class="footer-section">
            <h4>📜 Smart Meal Policies</h4>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Refund & Cancellation</a></li>
                <li><a href="#">Delivery Policy</a></li>
            </ul>
        </div>

        <!-- Quick Links Section -->
        <div class="footer-section">
            <h4>🍽️ Quick Links</h4>
            <ul>
                <li><a href="admin_homepage.php">Home</a></li>
                <li><a href="<?php echo $menu_dashboard; ?>">Menu Dashboard</a></li>
                <li><a href="#">Order Management</a></li>
                <li><a href="#">Customer Feedback</a></li>
            </ul>
        </div>

        <!-- Support Section -->
        <div class="footer-section">
            <h4>📞 Support</h4>
            <ul>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Contact Admin</a></li>
                <li><a href="#">24/7 Support</a></li>
                <li><a href="#">Report Issue</a></li>
            </ul>
        </div>

        <!-- Compliance Section -->
        <div class="footer-section">
            <h4>✅ Compliance</h4>
            <ul>
                <li><a href="#">Food Safety Standards</a></li>
                <li><a href="#">Halal Certified</a></li>
                <li><a href="#">Quality Assurance</a></li>
                <li><a href="#">ISO Certified</a></li>
            </ul>
        </div>

    </div>

    <!-- Copyright Notice -->
    <div class="copyright">
        &copy; <?php echo date('Y'); ?> Smart Meal Food Ordering System. All rights reserved. | Version 1.0
    </div>
</footer>

<!-- Simple debug log for developers -->
<script>
    console.log('Admin Homepage Ready - Connected to Menu Dashboard');
</script>

</body>
</html>