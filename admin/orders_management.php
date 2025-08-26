<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

// Fetch all orders from the database
$query = "SELECT orders.id, orders.user_id, orders.name, orders.email, orders.contact, orders.address, 
                 orders.payment_method, orders.total_amount, orders.payment_status, orders.order_status, 
                 orders.order_date, users.username 
          FROM orders 
          JOIN users ON orders.user_id = users.user_id";
$result = mysqli_query($conn, $query);

// Check for any errors
if (!$result) {
    echo "Error fetching orders: " . mysqli_error($conn);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Orders Management - Admin Dashboard</title>
    <style>
        .dashboard-container {
            width: 90%;
            margin: 50px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            color: #333;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .orders-table th,
        .orders-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .orders-table th {
            background-color: #f04e31;
            color: white;
        }

        .orders-table td .actions a,.back-btn {
            padding: 8px 15px;
            margin: 5px;
            text-decoration: none;
            background-color: #f04e31;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-btn{
            background-color: #234a21;
            
        }

        .orders-table td .actions a.update-status {
            background-color: #28a745;
        }

        .orders-table td .actions a.delete {
            background-color: #dc3545;
        }

        .orders-table td .actions a:hover {
            background-color: #d93b29;
        }

        .no-orders {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="dashboard-container">
        <h1>Orders Management</h1>
        <a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['username']; ?></td>
                            <td><?php echo $order['email']; ?></td>
                            <td><?php echo $order['total_amount']; ?></td>
                            <td><?php echo ucfirst($order['payment_status']); ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td>
                                <!-- Dropdown for changing the order status -->
                                <form action="update_order_status.php" method="POST">
                                    <select name="order_status" onchange="this.form.submit()">
                                        <option value="processing" <?php echo $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                </form>
                            </td>
                            <td class="actions">
                                <a href="delete_order.php?id=<?php echo $order['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders found in the database.</p>
        <?php endif; ?>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
