<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    // Update order status in the database
    $query = "UPDATE orders SET order_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $order_status, $order_id);

    if ($stmt->execute()) {
        header('Location: orders_management.php');
        exit();
    } else {
        echo "Error updating order status: " . mysqli_error($conn);
    }
}
?>
