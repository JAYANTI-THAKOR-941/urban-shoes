<?php
session_start();
include('../config/db_connection.php');
// Check if the cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/styles.css">
        <title>Your Cart</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                text-align: center;
            }
            .empty-cart-message {
                font-size: 20px;
                color: #555;
                margin-bottom: 20px;
            }
            .shopping-button {
                background-color: #2ecc71;
                color: white;
                border: none;
                padding: 12px 25px;
                font-size: 18px;
                cursor: pointer;
                border-radius: 5px;
                text-decoration: none;
                transition: background-color 0.3s;
            }
            .shopping-button:hover {
                background-color: #27ae60;
            }
        </style>
    </head>
    <body>
        <?php include('../includes/header.php'); ?>
        <div>
            <p class="empty-cart-message">Your cart is empty!</p>
            <a href="/urban-shoes/pages/shop.php" class="shopping-button">Go to Shopping</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle increase, decrease, and remove actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['productId'])) {
        $productId = intval($_POST['productId']); // Ensure it's an integer

        if ($productId <= 0) {
            echo "Invalid product ID!";
            exit;
        }

        // Increase, decrease, or remove action
        if ($_POST['action'] == 'increase') {
            $_SESSION['cart'][$productId]['quantity']++;
        } elseif ($_POST['action'] == 'decrease' && $_SESSION['cart'][$productId]['quantity'] > 1) {
            $_SESSION['cart'][$productId]['quantity']--;
        } elseif ($_POST['action'] == 'remove') {
            unset($_SESSION['cart'][$productId]);
        }

        header("Location: cart.php"); // Redirect to avoid resubmission
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Your Cart</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .cart-page {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .cart-table th, .cart-table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
        }

        .cart-table td {
            background-color: #f9f9f9;
        }

        .cart-table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        .cart-table button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cart-table button:hover {
            background-color: #2980b9;
        }

        .cart-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 18px;
        }

        .cart-summary h3 {
            color: #e74c3c;
            font-weight: bold;
        }

        .checkout-button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .checkout-button:hover {
            background-color: #27ae60;
        }

        .remove-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 5px;
        }

        .remove-button:hover {
            background-color: #c0392b;
        }

        .confirm-remove {
            padding: 6px 12px;
            background-color: #ff5722;
            color: white;
            border: none;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .confirm-remove:hover {
            background-color: #e64a19;
        }

        /* Mobile Responsiveness */
        @media screen and (max-width: 768px) {
            .cart-page {
                width: 95%;
                margin: 20px auto;
            }

            .cart-summary {
                flex-direction: column;
                align-items: flex-start;
            }

            .checkout-button {
                width: 100%;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="cart-page">
        <h1>Your Cart</h1>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalPrice = 0;
                // Fetch product data for each product in the cart
                foreach ($_SESSION['cart'] as $productId => $productData) {
                    $productId = intval($productId); // Ensure it's a valid number

                    if ($productId <= 0) {
                        continue; // Skip if the product ID is invalid
                    }

                    // Retrieve product from the database
                    $productQuery = "SELECT * FROM products WHERE id = $productId";
                    $productResult = mysqli_query($conn, $productQuery);
                    if ($productResult) {
                        $product = mysqli_fetch_assoc($productResult);
                        if ($product) {
                            $totalPrice += $product['price'] * $productData['quantity'];
                ?>
                            <tr>
                                <td><?php echo $product['name']; ?></td>
                                <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <form action="cart.php" method="POST" style="display:inline;">
                                        <button type="submit" name="action" value="increase">+</button>
                                        <span><?php echo $productData['quantity']; ?></span>
                                        <button type="submit" name="action" value="decrease">-</button>
                                        <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                    </form>
                                </td>
                                <td>₹<?php echo number_format($product['price'] * $productData['quantity'], 2); ?></td>
                                <td>
                                    <form action="cart.php" method="POST" style="display:inline;">
                                        <button type="submit" name="action" value="remove" class="remove-button" onclick="return confirm('Are you sure you want to remove this item?');">Remove</button>
                                        <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                    </form>
                                </td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "Error retrieving product details: " . mysqli_error($conn);
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Total: ₹<?php echo number_format($totalPrice, 2); ?></h3>

            <form action="checkout.php" method="POST">
                <button type="submit" class="checkout-button">Proceed to Checkout</button>
            </form>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
