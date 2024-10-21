<?php
// models.php

// Function to insert a new supplier
function insertSupplier($pdo, $supplier_name, $contact_info, $address) {
    $stmt = $pdo->prepare("INSERT INTO Suppliers (supplier_name, contact_info, address) VALUES (?, ?, ?)");
    return $stmt->execute([$supplier_name, $contact_info, $address]);
}

// Function to delete a supplier
function deleteSupplier($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Suppliers WHERE id = ?");
    return $stmt->execute([$id]);
}

// Function to Update a supplier
function updateSupplier($pdo, $id, $supplier_name, $contact_info, $address) {
    $stmt = $pdo->prepare("UPDATE Suppliers SET supplier_name = ?, contact_info = ?, address = ? WHERE id = ?");
    return $stmt->execute([$supplier_name, $contact_info, $address, $id]);
}

// Function to insert a new computer part
function insertComputerPart($pdo, $part_name, $price, $stock, $supplier_id) {
    $stmt = $pdo->prepare("INSERT INTO ComputerParts (part_name, price, stock, supplier_id) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$part_name, $price, $stock, $supplier_id]);
}

// Function to delete a computer part
function deleteComputerPart($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM ComputerParts WHERE id = ?");
    return $stmt->execute([$id]);
}

//Function to update a computer part
function updateComputerPart($pdo, $id, $part_name, $price, $stock, $supplier_id) {
    $stmt = $pdo->prepare("UPDATE ComputerParts SET part_name = ?, price = ?, stock = ?, supplier_id = ? WHERE id = ?");
    return $stmt->execute([$part_name, $price, $stock, $supplier_id, $id]);
}

// Function to insert a new customer
function insertCustomer($pdo, $first_name, $last_name, $email, $phone_number) {
    $stmt = $pdo->prepare("INSERT INTO Customers (first_name, last_name, email, phone_number) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$first_name, $last_name, $email, $phone_number]);
}

// Function to delete a customer
function deleteCustomer($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Customers WHERE id = ?");
    return $stmt->execute([$id]);
}

// Function to update a customer
function updateCustomer($pdo, $id, $first_name, $last_name, $email, $phone_number) {
    $stmt = $pdo->prepare("UPDATE Customers SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE id = ?");
    return $stmt->execute([$first_name, $last_name, $email, $phone_number, $id]);
}

// Function to insert a new order
function insertOrder($pdo, $order_date, $customer_id, $computer_part_id, $quantity) {
    $stmt = $pdo->prepare("INSERT INTO Orders (order_date, customer_id, computer_part_id, quantity) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$order_date, $customer_id, $computer_part_id, $quantity]);
}

// Function to delete an order
function deleteOrder($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Orders WHERE id = ?");
    return $stmt->execute([$id]);
}

// Function to update an order
function updateOrder($pdo, $id, $order_date, $customer_id, $computer_part_id, $quantity) {
    $stmt = $pdo->prepare("UPDATE Orders SET order_date = ?, customer_id = ?, computer_part_id = ?, quantity = ? WHERE id = ?");
    return $stmt->execute([$order_date, $customer_id, $computer_part_id, $quantity, $id]);
}

// Function to get all suppliers
function getAllSuppliers($pdo) {
    $stmt = $pdo->query("SELECT * FROM Suppliers");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all computer parts
function getAllComputerParts($pdo) {
    $stmt = $pdo->query("SELECT * FROM ComputerParts");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all customers
function getAllCustomers($pdo) {
    $stmt = $pdo->query("SELECT * FROM Customers");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all orders
function getAllOrders($pdo) {
    $stmt = $pdo->query("SELECT * FROM Orders");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get a supplier by ID
function getSupplierById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Suppliers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get a computer part by ID
function getComputerPartById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM ComputerParts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get a customer by ID
function getCustomerById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Customers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get an order by ID
function getOrderById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Orders WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// models.php
function getAllOrdersWithDetails($pdo) {
    $stmt = $pdo->prepare("
        SELECT o.id AS order_id, 
               o.order_date,  -- Include order_date
               c.first_name, 
               c.last_name, 
               cp.part_name, 
               o.quantity, 
               (o.quantity * cp.price) AS total_price 
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        JOIN ComputerParts cp ON o.computer_part_id = cp.id
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
