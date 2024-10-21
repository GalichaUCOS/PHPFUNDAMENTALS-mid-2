<?php
require_once 'dbConfig.php';
require_once 'models.php';

// Check if a form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle adding a supplier
    if (isset($_POST['add_supplier'])) {
        $supplier_name = $_POST['supplier_name'];
        $contact_info = $_POST['contact_info'];
        $address = $_POST['address'];
        insertSupplier($pdo, $supplier_name, $contact_info, $address);
    }

    // Handle adding a computer part
    if (isset($_POST['add_computer_part'])) {
        $part_name = $_POST['part_name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $supplier_id = $_POST['supplier_id']; // Ensure you have this value from the form
        insertComputerPart($pdo, $part_name, $price, $stock, $supplier_id);
    }

    // Handle adding a customer
    if (isset($_POST['add_customer'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        insertCustomer($pdo, $first_name, $last_name, $email, $phone_number);
    }

    // Handle adding an order
    if (isset($_POST['add_order'])) {
        $order_date = $_POST['order_date'];
        $customer_id = $_POST['customer_id']; // Ensure you have this value from the form
        $computer_part_id = $_POST['computer_part_id']; // Ensure you have this value from the form
        $quantity = $_POST['quantity'];
        insertOrder($pdo, $order_date, $customer_id, $computer_part_id, $quantity);
    }
}

// Handle delete actions
if (isset($_GET['delete'])) {
    $delete_type = $_GET['type'];
    $id = $_GET['id'];

    if ($delete_type === 'supplier') {
        deleteSupplier($pdo, $id);
    } elseif ($delete_type === 'computer_part') {
        deleteComputerPart($pdo, $id);
    } elseif ($delete_type === 'customer') {
        deleteCustomer($pdo, $id);
    } elseif ($delete_type === 'order') {
        deleteOrder($pdo, $id);
    }
}

// Retrieve data to display
$suppliers = getAllSuppliers($pdo);
$computerParts = getAllComputerParts($pdo);
$customers = getAllCustomers($pdo);
$orders = getAllOrdersWithDetails($pdo); // Assuming this function is implemented as per your models.php

// Fetch the most recent order details for the receipt
$latest_order = !empty($orders) ? $orders[count($orders) - 1] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Parts Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Computers Parts Order Management System</h1>

        <!-- Add Supplier Form -->
        <h2>Add Supplier</h2>
        <form method="POST">
            <input type="text" name="supplier_name" placeholder="Supplier Name" required>
            <input type="text" name="contact_info" placeholder="Contact Info" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="submit" name="add_supplier" value="Add Supplier">
        </form>

        <!-- Suppliers Table -->
        <h2>Suppliers</h2>
        <table>
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?php echo $supplier['supplier_name']; ?></td>
                        <td><?php echo $supplier['contact_info']; ?></td>
                        <td><?php echo $supplier['address']; ?></td>
                        <td>
                            <a href="edit.php?type=supplier&id=<?php echo $supplier['id']; ?>">Edit</a>
                            <a href="?delete=true&type=supplier&id=<?php echo $supplier['id']; ?>" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Computer Part Form -->
        <h2>Add Computer Part</h2>
        <form method="POST">
            <input type="text" name="part_name" placeholder="Part Name" required>
            <input type="number" name="price" placeholder="Price" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <select name="supplier_id" required>
                <option value="">Select Supplier</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['supplier_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" name="add_computer_part" value="Add Computer Part">
        </form>

        <!-- Computer Parts Table -->
        <h2>Computer Parts</h2>
        <table>
            <thead>
                <tr>
                    <th>Part Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($computerParts as $part): ?>
                    <tr>
                        <td><?php echo $part['part_name']; ?></td>
                        <td><?php echo $part['price']; ?></td>
                        <td><?php echo $part['stock']; ?></td>
                        <td><?php echo getSupplierById($pdo, $part['supplier_id'])['supplier_name']; ?></td>
                        <td>
                            <a href="edit.php?type=computer_part&id=<?php echo $part['id']; ?>">Edit</a>
                            <a href="?delete=true&type=computer_part&id=<?php echo $part['id']; ?>" onclick="return confirm('Are you sure you want to delete this part?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Customer Form -->
        <h2>Add Customer</h2>
        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone_number" placeholder="Phone Number" required>
            <input type="submit" name="add_customer" value="Add Customer">
        </form>

        <!-- Customers Table -->
        <h2>Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo $customer['first_name']; ?></td>
                        <td><?php echo $customer['last_name']; ?></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td><?php echo $customer['phone_number']; ?></td>
                        <td>
                            <a href="edit.php?type=customer&id=<?php echo $customer['id']; ?>">Edit</a>
                            <a href="?delete=true&type=customer&id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Order Form -->
        <h2>Add Order</h2>
        <form method="POST">
            <input type="datetime-local" name="order_date" required>
            <select name="customer_id" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer['id']; ?>"><?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="computer_part_id" required>
                <option value="">Select Computer Part</option>
                <?php foreach ($computerParts as $part): ?>
                    <option value="<?php echo $part['id']; ?>"><?php echo $part['part_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="submit" name="add_order" value="Add Order">
        </form>

                   <!-- Orders Table -->
        <h2>Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th> <!-- Added Order Date -->
                    <th>Customer</th>
                    <th>Part</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars(isset($order['order_date']) ? date('Y-m-d H:i', strtotime($order['order_date'])) : 'N/A'); ?></td> <!-- Displaying Order Date with default handling -->
                        <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['part_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo number_format($order['total_price'], 2); ?></td>
                        <td>
                            <a href="edit.php?type=order&id=<?php echo $order['order_id']; ?>">Edit</a>
                            <a href="?delete=true&type=order&id=<?php echo $order['order_id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
