<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User ID not found in session.");
}

require_once('../config/db_connection.php');

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Fetch user orders
$order_sql = "SELECT * FROM orders WHERE user_id = $user_id";
$order_result = $conn->query($order_sql);

// Debug: Check for errors in query execution
if ($conn->error) {
    die("Query Error: " . $conn->error);
}

// Debug: Check if any orders were found
if ($order_result->num_rows > 0) {
    $orders = $order_result->fetch_all(MYSQLI_ASSOC);
} else {
    $orders = []; // No orders found
}

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $cancel_order_id = $_POST['cancel_order_id'];
    $cancel_sql = "UPDATE orders SET order_status = 'Cancelled' WHERE id = $cancel_order_id AND user_id = $user_id";

    if ($conn->query($cancel_sql)) {
        header("Location: account.php");
        exit();
    } else {
        $error = "Failed to cancel the order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>
        /* Account Section */
        .account-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .account-left, .account-right {
            width: 48%;
            margin-bottom: 30px;
        }
        .heading {
            text-align: center;
        }

        /* Profile Section */
        .profile-info {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .profile-info img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-info .username-circle {
            width: 150px;
            height: 150px;
            background-color: #f04e31;
            color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 60px;
            margin-bottom: 20px;
        }

        .profile-info h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .profile-info p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .button {
            background-color: #f04e31;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 40px;
            transition: background-color 0.3s ease;
        }

        /* Orders Section */
        .orders-info {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .orders-info h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .order-item div {
            flex: 1;
        }

        .order-item p {
            margin: 5px 0;
            color: #555;
        }

        .order-item p strong {
            color: #f04e31;
        }

        .cancel-button {
            background-color: #ff0000;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .account-left, .account-right {
                width: 100%;
            }

            .button {
                width: auto;
            }
        }
    </style>
</head>
<body>
<?php include('../includes/header.php'); ?>

<div class="container">
    <!-- Header -->
    <h1 class="heading">My Account</h1>

    <!-- Account Content -->
    <div class="account-section">
        <!-- Profile Section -->
        <div class="account-left">
            <div class="profile-info">
                <?php if ($user['profile_image']): ?>
                    <img src="/urban-shoes/uploads/<?php echo basename($user['profile_image']); ?>" alt="Profile Image">
                <?php else: ?>
                    <div class="username-circle"><?php echo strtoupper($user['username'][0]); ?></div>
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['date_of_birth']); ?></p>

                <?php if ($user['phone'] && $user['address'] && $user['profile_image']): ?>
                    <a href="/urban-shoes/pages/update-profile.php" class="button">Update Profile</a>
                <?php else: ?>
                    <p><a href="/urban-shoes/pages/complete-profile.php" class="button">Complete Your Profile</a></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="account-right">
            <div class="orders-info">
                <h3>My Orders</h3>

                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div>
                                <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
                                <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
                                <p><strong>Status:</strong> <?php echo $order['order_status']; ?></p>
                            </div>
                            <div>
                                <p><strong>Total Amount:</strong> ₹<?php echo $order['total_amount']; ?></p>
                                <p><strong>Payment Status:</strong> <?php echo $order['payment_status']; ?></p>
                            </div>
                            <div>
                                <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                            </div>
                            <?php if ($order['order_status'] !== 'Cancelled'): ?>
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="cancel_order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" class="cancel-button">Cancel Order</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
