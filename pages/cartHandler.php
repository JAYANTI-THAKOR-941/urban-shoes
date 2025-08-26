<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Set a session variable to hold the login message
    $_SESSION['login_message'] = "Please login to continue.";

    // Redirect to login page with the current page's URL as the redirect parameter
    $redirectUrl = "login.php?redirect=" . urlencode($_SERVER['HTTP_REFERER']);
    
    // Output the JavaScript alert and then redirect
    echo "<script type='text/javascript'>
            alert('{$_SESSION['login_message']}');
            window.location.href = '$redirectUrl';
          </script>";
    exit;
}

include('../config/db_connection.php');

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($productId <= 0 || empty($action)) {
    echo "Invalid request.";
    exit;
}

$productQuery = "SELECT * FROM products WHERE id = $productId";
$productResult = mysqli_query($conn, $productQuery);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    echo "Product not found.";
    exit;
}

// Handle the add-to-cart action
if ($action === 'add') {
    // Initialize cart session if not already done
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add the product to the cart
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        ];
    } else {
        $_SESSION['cart'][$productId]['quantity']++;
    }

    // Redirect to the cart page
    header("Location: cart.php");
    exit;
} elseif ($action === 'remove') {
    // Remove the product from the cart
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    header("Location: cart.php");
    exit;
} elseif ($action === 'clear') {
    // Clear the entire cart
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
} else {
    echo "Invalid action.";
    exit;
}
?>
