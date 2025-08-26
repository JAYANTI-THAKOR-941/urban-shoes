<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

// Initialize counts
$user_count = 0;
$order_count = 0;
$product_count = 0;

// Query for total users
$user_query = mysqli_query($conn, "SELECT user_id FROM users");
if ($user_query) {
    $user_count = mysqli_num_rows($user_query);
} else {
    echo "Error fetching user count: " . mysqli_error($conn);
}

// Query for total orders
$order_query = mysqli_query($conn, "SELECT id FROM orders");
if ($order_query) {
    $order_count = mysqli_num_rows($order_query);
} else {
    echo "Error fetching order count: " . mysqli_error($conn);
}

// Query for total products
$product_query = mysqli_query($conn, "SELECT id FROM products");
if ($product_query) {
    $product_count = mysqli_num_rows($product_query);
} else {
    echo "Error fetching product count: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Dashboard</title>
    <style>
        .dashboard {
            width: 90%;
            margin: 50px auto;
        }
        .dashboard h1 {
            text-align: center;
            color: #333;
        }
        .cards {
            display: flex;
            gap: 20px;
            justify-content: space-around;
            margin-top: 30px;
        }
        .card {
            background: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 30%;
        }
        .card h2 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 18px;
            color: #666;
        }
        .links {
            text-align: center;
            margin-top: 40px;
        }
        .links a {
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            background-color: #f04e31;
            color: white;
            border-radius: 5px;
        }
        .links a:hover {
            background-color: #d93b29;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="dashboard">
        <h1>Welcome, Admin!</h1>
        <div class="cards">
            <div class="card">
                <h2><?php echo $user_count; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="card">
                <h2><?php echo $order_count; ?></h2>
                <p>Total Orders</p>
            </div>
            <div class="card">
                <h2><?php echo $product_count; ?></h2>
                <p>Total Products</p>
            </div>
        </div>
        <div class="links">
            <a href="user_management.php">Manage Users</a>
            <a href="orders_management.php">Manage Orders</a>
            <a href="product_dashboard.php">Manage Products</a>
            <a href="admin_logout.php">Logout</a>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
