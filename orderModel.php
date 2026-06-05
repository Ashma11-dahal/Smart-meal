<?php

/*
    OrderModel
    ----------
    This model contains all database operations for the Smart Meal project.
    Keeping SQL queries inside the model makes the pages cleaner and helps avoid
    repeating database code in many different files.
*/
class OrderModel
{
    private $conn;

    // Store the database connection for use in every method in this class.
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /*
        Get all order items from the orderitem table.
        The newest records are shown first because of ORDER BY id DESC.
    */
    public function getOrders()
    {
        $sql = "SELECT id, item_name, quantity, price, order_date, status FROM orderitem ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    /*
        Add a new order item.
        A prepared statement is used here so user input is sent safely to MySQL.
    */
    public function addOrder($itemName, $quantity, $price, $orderDate, $status)
    {
        // Insert the main item data plus the two newer fields:
        // order_date stores the DATE value and status stores the ENUM value.
        $sql = "INSERT INTO orderitem (item_name, quantity, price, order_date, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Bind values safely:
        // s = string, i = integer, d = decimal/double, s = date string, s = status string.
        $stmt->bind_param("sidss", $itemName, $quantity, $price, $orderDate, $status);
        return $stmt->execute();
    }

    /*
        Delete one order item by ID.
        The ID is bound as an integer so only the selected record is deleted.
    */
    public function deleteOrder($id)
    {
        $sql = "DELETE FROM orderitem WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /*
        Get one order item by ID.
        This is used by edit.php to fill the edit form with the current values.
    */
    public function getOrderById($id)
    {
        $sql = "SELECT id, item_name, quantity, price, order_date, status FROM orderitem WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows === 1 ? $result->fetch_assoc() : null;
    }

    /*
        Update an existing order item.
        This is called from update.php after the submitted edit form passes validation.
    */
    public function updateOrder($id, $itemName, $quantity, $price, $orderDate, $status)
    {
        // Update every editable column for the selected order item record.
        // The WHERE id = ? part makes sure only one row is changed.
        $sql = "UPDATE orderitem SET item_name = ?, quantity = ?, price = ?, order_date = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // The final i is the order item ID.
        $stmt->bind_param("sidssi", $itemName, $quantity, $price, $orderDate, $status, $id);
        return $stmt->execute();
    }

    /*
        Search order items by item name.
        LIKE allows partial matches, so searching "momo" can find "Jhol momo".
    */
    public function searchOrder($keyword, $status = "")
    {
        // Start with item-name search. If keyword is empty, LIKE '%%' matches all item names.
        $sql = "SELECT id, item_name, quantity, price, order_date, status FROM orderitem WHERE item_name LIKE ?";
        $searchText = "%" . $keyword . "%";

        // Add the status filter only when the user selected a status.
        if ($status !== "") {
            $sql .= " AND status = ?";
        }

        // Show newest order items first.
        $sql .= " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);

        // Bind one or two parameters depending on whether status filtering is active.
        if ($status !== "") {
            $stmt->bind_param("ss", $searchText, $status);
        } else {
            $stmt->bind_param("s", $searchText);
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    /*
        Filter order items by status.
        This is used when the user wants to see only Pending, Processed, or Cancelled items.
    */
    public function filterOrdersByStatus($status)
    {
        $sql = "SELECT id, item_name, quantity, price, order_date, status FROM orderitem WHERE status = ? ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();

        return $stmt->get_result();
    }

    /*
        Get available menu items for the public website menu page.
        This reads from menu_items, which is used for the customer-facing menu.
    */
    public function getAvailableMenuItems()
    {
        $sql = "SELECT MenuItemID AS id, Name AS name, Price AS price, Category AS category
                FROM menu_items
                WHERE IsAvailable = 1
                ORDER BY Category ASC, Name ASC";

        try {
            return $this->conn->query($sql);
        } catch (mysqli_sql_exception $exception) {
            // If the menu_items table is missing, return false so the page can show fallback data.
            return false;
        }
    }
}

?>
