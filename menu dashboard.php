<?php
// ============================================
// SmartMeal - Menu Items Management Dashboard (PDO Version)
// ============================================
// This page allows admin/staff to view, add, edit, delete,
// search, and filter all menu items in the system.
// Uses PDO for database interaction.
// ============================================

session_start();

// Check if user is logged in
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header("Location: views/staff_login.php");
    exit();
}

// ============================================
// DATABASE CONNECTION (PDO)
// ============================================
require_once __DIR__ . '/../views/db.php';   // adjust path to your Database class
$database = new Database();
$pdo = $database->getConnection();

// ============================================
// FUNCTION: LIST ALL MENU ITEMS (PDO version)
// ============================================
function listAllMenuItems($pdo, $search = '', $filter = '') {
    // Base query
    $sql = "SELECT * FROM MenuItems WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND Name LIKE :search";
        $params[':search'] = "%$search%";
    }
    if (!empty($filter)) {
        $sql .= " AND Category = :filter";
        $params[':filter'] = $filter;
    }
    $sql .= " ORDER BY MenuItemID DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        echo '<div class="table-container">';
        echo '<h3>📋 Menu Items List (' . count($result) . ' items found)</h3>';
        echo '<table class="menu-table">';
        echo '<thead><tr>
                <th>ID</th><th>Item Name</th><th>Price</th>
                <th>Category</th><th>Date Added</th><th>Status</th><th>Actions</th>
              </tr></thead><tbody>';

        foreach ($result as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['MenuItemID']) . '</td>';
            echo '<td><strong>' . htmlspecialchars($row['Name']) . '</strong></td>';
            echo '<td>$' . number_format($row['Price'], 2) . '</td>';
            echo '<td><span class="category-badge">' . htmlspecialchars($row['Category']) . '</span></td>';
            echo '<td>' . htmlspecialchars($row['DateAdded']) . '</td>';
            echo '<td>';
            if ($row['IsAvailable'] == 1) {
                echo '<span class="status-available">✅ Available</span>';
            } else {
                echo '<span class="status-unavailable">❌ Unavailable</span>';
            }
            echo '</td>';
            echo '<td>';
            echo '<a href="?edit=' . $row['MenuItemID'] . '" class="btn-edit">✏️ Edit</a>';
            echo '<a href="?delete=' . $row['MenuItemID'] . '" class="btn-delete" onclick="return confirm(\'Delete this item?\')">🗑️ Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></div>';
    } else {
        echo '<div class="empty-state">';
        echo '<p>📭 No menu items found.</p>';
        echo '<p>Click "Add New Item" to create your first menu item.</p>';
        echo '</div>';
    }
}

// ============================================
// ADD / UPDATE / DELETE (PDO)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = $_POST['MenuItemID'] ?? null;
    $name = trim($_POST['Name']);
    $price = $_POST['Price'];
    $category = $_POST['Category'];
    $date = $_POST['DateAdded'];
    $available = isset($_POST['IsAvailable']) ? 1 : 0;

    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($price) || $price <= 0) $errors[] = "Valid price is required";
    if (empty($category)) $errors[] = "Category is required";

    if (empty($errors)) {
        if ($id) {
            // UPDATE
            $sql = "UPDATE MenuItems SET Name=:name, Price=:price, Category=:category, 
                    DateAdded=:date, IsAvailable=:available WHERE MenuItemID=:id";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                ':name' => $name, ':price' => $price, ':category' => $category,
                ':date' => $date, ':available' => $available, ':id' => $id
            ]);
            $msg = $success ? "Item updated successfully!" : "Database error: " . implode(" ", $pdo->errorInfo());
        } else {
            // INSERT
            $sql = "INSERT INTO MenuItems (Name, Price, Category, DateAdded, IsAvailable)
                    VALUES (:name, :price, :category, :date, :available)";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                ':name' => $name, ':price' => $price, ':category' => $category,
                ':date' => $date, ':available' => $available
            ]);
            $msg = $success ? "Item added successfully!" : "Database error: " . implode(" ", $pdo->errorInfo());
        }
        if ($success) {
            $success_message = $msg;
        } else {
            $error_message = $msg;
        }
    } else {
        $error_message = implode(", ", $errors);
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM MenuItems WHERE MenuItemID = :id");
    if ($stmt->execute([':id' => $id])) {
        $success_message = "Item deleted successfully!";
    } else {
        $error_message = "Delete failed!";
    }
}

// EDIT: load item data
$editItem = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM MenuItems WHERE MenuItemID = :id");
    $stmt->execute([':id' => $id]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Search & filter parameters
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Meal - Menu Dashboard | PDO Version</title>
    <style>
        /* ===== Your existing CSS (unchanged) ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        body {
            background: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #1a472a, #2d5a3f);
            color: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 28px; }
        .header span { color: #ff6600; }
        .user-info {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 25px;
        }
        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .search-filter-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .search-box { flex: 2; display: flex; gap: 10px; }
        .search-box input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; }
        .filter-box { flex: 1; display: flex; gap: 10px; }
        .filter-box select { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; }
        button {
            padding: 10px 20px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #e55a00; transform: translateY(-2px); }
        .btn-add { background: #28a745; padding: 10px 25px; }
        .btn-add:hover { background: #218838; }
        .form-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .form-box h2 {
            color: #1a472a;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ff6600;
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        .table-container h3 { margin-bottom: 15px; color: #1a472a; }
        .menu-table { width: 100%; border-collapse: collapse; }
        .menu-table th {
            background: #1a472a;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        .menu-table td { padding: 12px; border-bottom: 1px solid #eee; }
        .menu-table tr:hover { background: #f9f9f9; }
        .category-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-available { color: #28a745; font-weight: 600; }
        .status-unavailable { color: #dc3545; font-weight: 600; }
        .btn-edit, .btn-delete {
            display: inline-block;
            padding: 5px 12px;
            margin: 0 3px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            transition: 0.3s;
        }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-edit:hover { background: #e0a800; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #c82333; }
        .empty-state { text-align: center; padding: 60px 20px; color: #666; }
        .empty-state p { margin: 10px 0; }
        .footer {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            margin-top: 20px;
        }
        .stats {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h1>Smart<span>Meal</span></h1>
            <p>Menu Items Management System</p>
        </div>
        <div class="user-info">
            👋 Welcome, <?php echo $_SESSION['staff_name'] ?? 'Admin'; ?>
        </div>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="success-msg">✅ <?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="error-msg">❌ <?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Search and Filter Bar -->
    <div class="search-filter-bar">
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="🔍 Search by item name..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
            <?php if ($search || $filter): ?>
                <a href="?"><button type="button">Clear</button></a>
            <?php endif; ?>
        </form>
        <form method="GET" class="filter-box">
            <select name="filter">
                <option value="">All Categories</option>
                <option value="Pizza" <?php echo $filter == 'Pizza' ? 'selected' : ''; ?>>🍕 Pizza</option>
                <option value="Burger" <?php echo $filter == 'Burger' ? 'selected' : ''; ?>>🍔 Burger</option>
                <option value="Snacks" <?php echo $filter == 'Snacks' ? 'selected' : ''; ?>>🍟 Snacks</option>
                <option value="Drinks" <?php echo $filter == 'Drinks' ? 'selected' : ''; ?>>🥤 Drinks</option>
            </select>
            <button type="submit">Filter</button>
        </form>
        <a href="?"><button class="btn-add">➕ Add New Item</button></a>
    </div>

    <!-- Add / Edit Form -->
    <?php if (isset($_GET['edit']) || isset($_GET['add'])): ?>
    <div class="form-box">
        <h2><?php echo $editItem ? "✏️ Edit Menu Item" : "➕ Add New Menu Item"; ?></h2>
        <form method="POST">
            <input type="hidden" name="MenuItemID" value="<?php echo $editItem['MenuItemID'] ?? ''; ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Item Name *</label>
                    <input type="text" name="Name" value="<?php echo $editItem['Name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Price *</label>
                    <input type="number" step="0.01" name="Price" value="<?php echo $editItem['Price'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="Category" required>
                        <option value="">Select Category</option>
                        <option value="Pizza" <?php echo (isset($editItem['Category']) && $editItem['Category'] == 'Pizza') ? 'selected' : ''; ?>>Pizza</option>
                        <option value="Burger" <?php echo (isset($editItem['Category']) && $editItem['Category'] == 'Burger') ? 'selected' : ''; ?>>Burger</option>
                        <option value="Snacks" <?php echo (isset($editItem['Category']) && $editItem['Category'] == 'Snacks') ? 'selected' : ''; ?>>Snacks</option>
                        <option value="Drinks" <?php echo (isset($editItem['Category']) && $editItem['Category'] == 'Drinks') ? 'selected' : ''; ?>>Drinks</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date Added</label>
                    <input type="date" name="DateAdded" value="<?php echo $editItem['DateAdded'] ?? date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="IsAvailable" <?php echo (isset($editItem['IsAvailable']) && $editItem['IsAvailable']) ? 'checked' : ''; ?>>
                    ✅ Available for ordering
                </label>
            </div>
            <button type="submit" name="save"><?php echo $editItem ? "Update Item" : "Save Item"; ?></button>
            <a href="?" style="margin-left: 10px;"><button type="button">Cancel</button></a>
        </form>
    </div>
    <?php endif; ?>

    <!-- Display Menu Items List -->
    <?php listAllMenuItems($pdo, $search, $filter); ?>

    <!-- Footer with Statistics -->
    <div class="footer">
        <p>© 2026 SmartMeal Food Ordering System | Menu Management Dashboard</p>
        <div class="stats">
            <span>📋 Total Items: 
                <?php 
                $total = $pdo->query("SELECT COUNT(*) FROM MenuItems")->fetchColumn();
                echo $total;
                ?>
            </span>
            <span>✅ Available: 
                <?php 
                $available = $pdo->query("SELECT COUNT(*) FROM MenuItems WHERE IsAvailable=1")->fetchColumn();
                echo $available;
                ?>
            </span>
            <span>📁 Categories: Pizza, Burger, Snacks, Drinks</span>
        </div>
    </div>
</div>

<?php include '../views/footer.php'; ?>
</body>
</html>