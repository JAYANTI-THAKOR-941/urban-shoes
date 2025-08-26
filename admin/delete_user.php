<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Query to delete the user
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $delete_query)) {
        header('Location: user_management.php');
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
        exit();
    }
} else {
    echo "Invalid user ID.";
    exit();
}
?>
