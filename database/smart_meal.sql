CREATE TABLE customers (
    customerID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    isActive BOOLEAN DEFAULT TRUE,
    createdDate DATE NOT NULL
);

CREATE TABLE staff (
    staffID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    isActive BOOLEAN DEFAULT TRUE,
    createdDate DATE NOT NULL
);

CREATE TABLE menu_items (
    menuItemID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    isAvailable BOOLEAN DEFAULT TRUE,
    createdDate DATE NOT NULL
);

CREATE TABLE orders (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    customerID INT NOT NULL,
    staffID INT,
    orderDate DATE NOT NULL,
    totalAmount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    isCompleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (customerID) REFERENCES customers(customerID),
    FOREIGN KEY (staffID) REFERENCES staff(staffID)
);

CREATE TABLE order_items (
    orderItemID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT NOT NULL,
    menuItemID INT NOT NULL,
    quantity INT NOT NULL,
    unitPrice DECIMAL(10,2) NOT NULL,
    subTotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES orders(orderID),
    FOREIGN KEY (menuItemID) REFERENCES menu_items(menuItemID)
);