<?php
include('../config/db_connection.php');
session_start();

// Get product ID from query parameter
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$productQuery = "SELECT * FROM products WHERE id = $productId";
$productResult = mysqli_query($conn, $productQuery);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    echo "<p>Product not found!</p>";
    exit;
}

// Fetch product images
$imageQuery = "SELECT image_url, is_main FROM product_images WHERE product_id = $productId";
$imageResult = mysqli_query($conn, $imageQuery);

$mainImage = null;
$images = [];
while ($image = mysqli_fetch_assoc($imageResult)) {
    if ($image['is_main']) {
        $mainImage = $image['image_url'];
    }
    $images[] = $image['image_url'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title><?php echo $product['name']; ?> - Urban Shoes</title>
    <style>
        /* Product Page Container */
        .product-page {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Header Section */
        .product-header {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Product Image */
        .product-images {
            flex: 1;
            max-width:450px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product-images img {
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-images img:hover {
            transform: scale(1.05);
        }

        /* Product Details */
        .product-details {
            flex: 1;
            max-width: 600px;
            text-align: left;
        }

        .product-details h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-details .price {
            font-size: 26px;
            color: #f04e31;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-description {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            line-height: 1.6;
            color: #555;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Product Size Selector */
        .product-size {
            margin-top: 20px;
            font-size: 16px;
        }

        .product-size select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 150px;
            margin-top: 10px;
        }

        /* Buttons */
        .product-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .product-actions button {
            padding: 12px 25px;
            background-color: #f04e31;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 200px;
        }

        .product-actions button:hover {
            background-color: #e03a1d;
        }

        /* Thumbnails */
        .product-thumbnails {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .product-thumbnails img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 2px solid #ddd;
            cursor: pointer;
            border-radius: 5px;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .product-thumbnails img:hover,
        .product-thumbnails img.active {
            transform: scale(1.1);
            border-color: #f04e31;
        }

    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="product-page">
        <div class="product-header">
            <div class="product-images">
                <img src="../assets/images/<?php echo $mainImage; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="product-details">
                <h1><?php echo $product['name']; ?></h1>
                <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo $product['description']; ?></p>

                <!-- Shoe Size Selection -->
                <div class="product-size">
                    <label for="shoe-size">Select Size:</label>
                    <select id="shoe-size" name="shoe_size" form="add-to-cart-form" required>
                        <option value="" disabled selected>Select size</option>
                        <?php
                        $availableSizes = ['6', '7', '8', '9', '10', '11'];
                        foreach ($availableSizes as $size) {
                            echo "<option value='$size'>$size</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Add to Cart and Buy Now Buttons -->
                <div class="product-actions">
                    <form id="add-to-cart-form" action="cartHandler.php" method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="size" id="shoe-size-hidden" value="">
                        <button type="submit" class="btn-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Thumbnails -->
        <div class="product-thumbnails">
            <?php foreach ($images as $image): ?>
                <img src="../assets/images/<?php echo $image; ?>" alt="Thumbnail" onclick="changeImage('<?php echo $image; ?>')">
            <?php endforeach; ?>
        </div>

        <div class="product-description">
            <h2>About this Product</h2>
            <p><?php echo $product['description']; ?></p>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script>
        function changeImage(image) {
            document.querySelector('.product-images img').src = '../assets/images/' + image;
        }

        // Ensure shoe size is selected for "Buy Now" and "Add to Cart"
        document.querySelector('.btn-cart').addEventListener('click', (e) => {
            const selectedSize = document.getElementById('shoe-size').value;
            if (!selectedSize) {
                alert('Please select a shoe size before proceeding.');
                e.preventDefault();
            } else {
                document.getElementById('shoe-size-hidden').value = selectedSize;
            }
        });

        document.querySelector('.btn-buy').addEventListener('click', (e) => {
            const selectedSize = document.getElementById('shoe-size').value;
            if (!selectedSize) {
                alert('Please select a shoe size before proceeding.');
                e.preventDefault();
            } else {
                document.getElementById('shoe-size-hidden-checkout').value = selectedSize;
            }
        });
    </script>
</body>
</html>
