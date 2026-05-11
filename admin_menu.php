

<?php

session_start();
include "db.php";

/*
=====================================================
SECURITY CHECK
=====================================================
*/

if (!isset($_SESSION['staff'])) {

    header("Location: index.php");
    exit();
}

/*
=====================================================
DELETE ITEM
=====================================================
*/

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM menu_items WHERE MenuItemID = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
}

/*
=====================================================
ADD ITEM
=====================================================
*/

if (isset($_POST['add_item'])) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $available = $_POST['available'];

    /*
    =============================================
    VALIDATION
    =============================================
    */

    if (
        empty($name) ||
        empty($price) ||
        empty($category)
    ) {

        $message = "All fields are required.";

    } elseif (!is_numeric($price)) {

        $message = "Price must be numeric.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO menu_items
            (Name, Price, Category, DateAdded, IsAvailable)
            VALUES (?, ?, ?, NOW(), ?)"
        );

        $stmt->bind_param(
            "sdsi",
            $name,
            $price,
            $category,
            $available
        );

        $stmt->execute();

        $message = "Menu item added successfully.";
    }
}

/*
=====================================================
UPDATE ITEM
=====================================================
*/

if (isset($_POST['update_item'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $available = $_POST['available'];

    $stmt = $conn->prepare(
        "UPDATE menu_items
         SET Name=?,
             Price=?,
             Category=?,
             IsAvailable=?
         WHERE MenuItemID=?"
    );

    $stmt->bind_param(
        "sdsii",
        $name,
        $price,
        $category,
        $available,
        $id
    );

    $stmt->execute();

    $message = "Menu item updated successfully.";
}

/*
=====================================================
FILTER SEARCH
=====================================================
*/

$search = "";

if (isset($_GET['search'])) {

    $search = $_GET['search'];

    $stmt = $conn->prepare(
        "SELECT * FROM menu_items
         WHERE Name LIKE ?
         OR Category LIKE ?
         ORDER BY MenuItemID DESC"
    );

    $like = "%$search%";

    $stmt->bind_param("ss", $like, $like);

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query(
        "SELECT * FROM menu_items
         ORDER BY MenuItemID DESC"
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>SmartMeal Admin Dashboard</title>

<style>

/* ==========================================
   GLOBAL
========================================== */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f4f4f4;
}

/* ==========================================
   SIDEBAR
========================================== */

.sidebar{

    position:fixed;
    width:250px;
    height:100%;
    background:#28a745;
    padding:30px;
    color:white;
}

.sidebar h2{
    margin-bottom:40px;
}

.sidebar a{

    display:block;
    color:white;
    text-decoration:none;
    margin-bottom:20px;
    font-size:18px;
    transition:0.3s;
}

.sidebar a:hover{
    padding-left:10px;
}

/* ==========================================
   MAIN CONTENT
========================================== */

.main{

    margin-left:250px;
    padding:40px;
}

.header{

    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.header h1{
    color:#333;
}

.logout{

    background:#ff6600;
    color:white;
    padding:10px 20px;
    border-radius:5px;
    text-decoration:none;
}

/* ==========================================
   MESSAGE
========================================== */

.message{

    background:#d4edda;
    color:#155724;
    padding:15px;
    margin-bottom:20px;
    border-radius:5px;
}

/* ==========================================
   FORM
========================================== */

.form-container{

    background:white;
    padding:30px;
    border-radius:10px;
    margin-bottom:40px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.form-container h2{
    margin-bottom:20px;
}

.form-group{
    margin-bottom:15px;
}

.form-group input,
.form-group select{

    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:5px;
}

.btn{

    background:#28a745;
    color:white;
    border:none;
    padding:12px 20px;
    cursor:pointer;
    border-radius:5px;
}

/* ==========================================
   SEARCH
========================================== */

.search-box{

    margin-bottom:25px;
}

.search-box input{

    width:300px;
    padding:12px;
    border:1px solid #ccc;
    border-radius:5px;
}

/* ==========================================
   TABLE
========================================== */

table{

    width:100%;
    background:white;
    border-collapse:collapse;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

table th{

    background:#28a745;
    color:white;
    padding:15px;
}

table td{

    padding:15px;
    border-bottom:1px solid #ddd;
}

.edit-btn{

    background:#007bff;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.delete-btn{

    background:red;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

/* ==========================================
   RESPONSIVE
========================================== */

@media(max-width:900px){

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .main{
        margin-left:0;
    }
}

</style>

</head>

<body>

<!-- =======================================
     SIDEBAR
======================================= -->

<div class="sidebar">

    <h2>SmartMeal</h2>

    <a href="#">Dashboard</a>
    <a href="#">Menu Management</a>
    <a href="#">Orders</a>
    <a href="#">Customers</a>
    <a href="logout.php">Logout</a>

</div>

<!-- =======================================
     MAIN CONTENT
======================================= -->

<div class="main">

    <div class="header">

        <h1>Menu Items Management</h1>

        <a class="logout"
           href="logout.php">
            Logout
        </a>

    </div>

    <!-- SUCCESS MESSAGE -->

    <?php
    if (isset($message)) {
        echo "<div class='message'>$message</div>";
    }
    ?>

    <!-- ===================================
         ADD ITEM FORM
    ==================================== -->

    <div class="form-container">

        <h2>Add New Menu Item</h2>

        <form method="POST">

            <div class="form-group">

                <input type="text"
                       name="name"
                       placeholder="Item Name"
                       required>

            </div>

            <div class="form-group">

                <input type="number"
                       step="0.01"
                       name="price"
                       placeholder="Price"
                       required>

            </div>

            <div class="form-group">

                <input type="text"
                       name="category"
                       placeholder="Category"
                       required>

            </div>

            <div class="form-group">

                <select name="available">

                    <option value="1">
                        Available
                    </option>

                    <option value="0">
                        Not Available
                    </option>

                </select>

            </div>

            <button class="btn"
                    type="submit"
                    name="add_item">

                Add Menu Item

            </button>

        </form>

    </div>

    <!-- ===================================
         SEARCH FILTER
    ==================================== -->

    <div class="search-box">

        <form method="GET">

            <input type="text"
                   name="search"
                   placeholder="Search item/category..."
                   value="<?= $search ?>">

            <button class="btn">
                Search
            </button>

        </form>

    </div>

    <!-- ===================================
         MENU TABLE
    ==================================== -->

    <table>

        <tr>

            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Date Added</th>
            <th>Available</th>
            <th>Actions</th>

        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?= $row['MenuItemID'] ?>
            </td>

            <td>
                <?= $row['Name'] ?>
            </td>

            <td>
                $<?= $row['Price'] ?>
            </td>

            <td>
                <?= $row['Category'] ?>
            </td>

            <td>
                <?= $row['DateAdded'] ?>
            </td>

            <td>

                <?= $row['IsAvailable']
                    ? "Yes"
                    : "No" ?>

            </td>

            <td>

                <!-- EDIT BUTTON -->

                <a class="edit-btn"
                   href="?edit=<?= $row['MenuItemID'] ?>">

                    Edit

                </a>

                <!-- DELETE BUTTON -->

                <a class="delete-btn"
                   onclick="return confirm('Delete this item?')"
                   href="?delete=<?= $row['MenuItemID'] ?>">

                    Delete

                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

    <!-- ===================================
         EDIT FORM
    ==================================== -->

    <?php

    if (isset($_GET['edit'])) {

        $id = $_GET['edit'];

        $stmt = $conn->prepare(
            "SELECT * FROM menu_items
             WHERE MenuItemID=?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $editData =
            $stmt->get_result()->fetch_assoc();
    ?>

    <br><br>

    <div class="form-container">

        <h2>Edit Menu Item</h2>

        <form method="POST">

            <input type="hidden"
                   name="id"
                   value="<?= $editData['MenuItemID'] ?>">

            <div class="form-group">

                <input type="text"
                       name="name"
                       value="<?= $editData['Name'] ?>"
                       required>

            </div>

            <div class="form-group">

                <input type="number"
                       step="0.01"
                       name="price"
                       value="<?= $editData['Price'] ?>"
                       required>

            </div>

            <div class="form-group">

                <input type="text"
                       name="category"
                       value="<?= $editData['Category'] ?>"
                       required>

            </div>

            <div class="form-group">

                <select name="available">

                    <option value="1"
                    <?= $editData['IsAvailable']
                        ? 'selected'
                        : '' ?>>

                        Available

                    </option>

                    <option value="0"
                    <?= !$editData['IsAvailable']
                        ? 'selected'
                        : '' ?>>

                        Not Available

                    </option>

                </select>

            </div>

            <button class="btn"
                    type="submit"
                    name="update_item">

                Update Item

            </button>

        </form>

    </div>

    <?php } ?>

</div>

</body>
</html>