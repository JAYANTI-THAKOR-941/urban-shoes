<?php
session_start();
include('../config/db_connection.php');

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (empty($_SESSION['cart'])) {
    echo "<p class='empty-cart-message'>Your cart is empty! Please add items to the cart before checking out.</p>";
    exit;
}

$cartTotal = 0;
foreach ($_SESSION['cart'] as $product) {
    if (isset($product['price'], $product['quantity'])) {
        $cartTotal += $product['price'] * $product['quantity'];
    }
}

$keyId = 'rzp_test_MCCHlSWeh3mRj4';
$keySecret = 'hTSHYNC8Cm084lPqg9AdejH7';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['contact'], $_POST['address'], $_POST['payment-method'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $paymentMethod = $_POST['payment-method'];

        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Insert order data into the database, including the user_id
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, contact, address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssd", $userId, $name, $email, $contact, $address, $paymentMethod, $cartTotal);

        if ($stmt->execute()) {
            $orderId = $stmt->insert_id;

            // Insert order items into the order_items table
            foreach ($_SESSION['cart'] as $product) {
                if (isset($product['id'], $product['quantity'], $product['price'])) {
                    $productId = $product['id'];
                    $quantity = $product['quantity'];
                    $price = $product['price'];
                    $totalPrice = $quantity * $price;

                    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)");
                    $stmt2->bind_param("iiidd", $orderId, $productId, $quantity, $price, $totalPrice);
                    $stmt2->execute();
                }
            }

            // Clear the cart session
            $_SESSION['cart'] = [];

            // If payment is COD, redirect to success page
            if ($paymentMethod == 'cod') {
                header('Location: success.php');
                exit;
            }

            // Otherwise, continue to Razorpay payment process
            header('Location: payment.php');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Urban Shoes</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .checkout-container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .heading-primary {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .cart-summary h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .cart-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart-summary table th,
        .cart-summary table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .cart-summary .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #e91e63;
            margin-top: 10px;
        }

        .form-section {
            margin-top: 30px;
        }

        .form-section h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .input-field,
        .textarea-field,
        .select-field {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .textarea-field {
            height: 120px;
        }

        .payment-option {
            margin-bottom: 20px;
        }

        .process-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #ff5722;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .process-btn:hover {
            background-color: #e64a19;
        }

        .empty-cart-message {
            text-align: center;
            font-size: 18px;
            color: #e91e63;
        }

        @media (max-width: 768px) {
            .checkout-container {
                width: 95%;
                padding: 15px;
            }

            .heading-primary {
                font-size: 24px;
            }

            .cart-summary h2,
            .form-section h2 {
                font-size: 20px;
            }

            .input-field,
            .textarea-field,
            .select-field {
                font-size: 14px;
            }

            .process-btn {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="checkout-container">
        <h1 class="heading-primary">Checkout</h1>
        <div class="cart-summary">
            <h2>Your Cart</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product): ?>
                        <?php $totalPrice = $product['price'] * $product['quantity']; ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td>₹<?= number_format($product['price'], 2); ?></td>
                            <td><?= $product['quantity']; ?></td>
                            <td>₹<?= number_format($totalPrice, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3 class="total-price">Total: ₹<?= number_format($cartTotal, 2); ?></h3>
        </div>

        <div class="form-section">
            <h2>Enter Your Details</h2>
            <form method="POST" id="checkout-form">
                <label for="name" class="label">Full Name:</label>
                <input type="text" id="name" name="name" required class="input-field">

                <label for="email" class="label">Email:</label>
                <input type="email" id="email" name="email" required class="input-field">

                <label for="contact" class="label">Contact Number:</label>
                <input type="text" id="contact" name="contact" required class="input-field">

                <label for="address" class="label">Shipping Address:</label>
                <textarea id="address" name="address" required class="textarea-field"></textarea>

                <div class="payment-option">
                    <label for="payment-method" class="label">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required class="select-field">
                        <option value="online">Online Payment</option>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" class="process-btn">Proceed to Payment</button>
            </form>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script>
        document.getElementById('checkout-form').onsubmit = function(event) {
            event.preventDefault();

            const paymentMethod = document.getElementById('payment-method').value;
            if (paymentMethod === 'online') {
                const options = {
                    key: "<?= $keyId; ?>",
                    amount: "<?= $cartTotal * 100; ?>",
                    currency: "INR",
                    name: "Urban Shoes",
                    description: "Checkout Payment",
                    image: "https://your-website-logo.png",
                    handler: function (response) {
                        alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
                        document.getElementById('checkout-form').submit();
                    },
                    prefill: {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        contact: document.getElementById('contact').value,
                    },
                    notes: {
                        address: document.getElementById('address').value
                    },
                    theme: {
                        color: "#ff5722"
                    }
                };

                const razorpay = new Razorpay(options);
                razorpay.open();
            } else {
                // If cash on delivery, directly submit the form
                document.getElementById('checkout-form').submit();
            }
        };
    </script>
</body>
</html>
