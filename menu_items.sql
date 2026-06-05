CREATE TABLE IF NOT EXISTS menu_items (
    MenuItemID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Category VARCHAR(100) NOT NULL,
    DateAdded DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    IsAvailable TINYINT(1) NOT NULL DEFAULT 1
);

INSERT INTO menu_items (Name, Price, Category, IsAvailable) VALUES
('Chicken Dumplings', 10.00, 'Starter', 1),
('Samosa', 15.00, 'Starter', 1),
('Wings', 8.00, 'Snack', 1),
('Thakali Set', 20.00, 'Main Course', 1),
('Tea', 0.01, 'Drink', 1),
('Seasonal Soup', 5.50, 'Starter', 1);
