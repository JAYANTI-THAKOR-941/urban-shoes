<?php
include('../config/db_connection.php');

// Check if the product ID is provided
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch the existing product data
    $query = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    // If the product does not exist, redirect to the admin dashboard
    if (!$product) {
        header('Location: dashboard.php');
        exit;
    }

    // Handle form submission for updating the product
    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_FILES['image']['name'];

        // If a new image is uploaded, move it to the images folder
        if ($image) {
            $targetDir = "../assets/images/";
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        } else {
            // Use the current image if no new image is uploaded
            $image = $product['image'];
        }

        // Update the product details in the database
        $updateQuery = "UPDATE products SET name = '$name', description = '$description', price = '$price', category = '$category', image = '$image' WHERE id = $productId";
        mysqli_query($conn, $updateQuery);

        // Redirect back to the admin dashboard after updating
        header('Location: product_dashboard.php');
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
    <title>Update Product - Admin Dashboard</title>
    <style>
        .create-product-container {
            width: 60%;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .create-product-container h1 {
            text-align: center;
            color: #333;
        }

        .create-product-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .create-product-form label {
            font-weight: bold;
            color: #555;
        }

        .create-product-form input,
        .create-product-form textarea,
        .create-product-form button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .create-product-form textarea {
            resize: none;
            height: 100px;
        }

        .create-product-form button {
            background-color: #f04e31;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .create-product-form button:hover {
            background-color: #f04e31;
        }

        .create-product-message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .create-product-message.success {
            color: green;
        }

        .create-product-message.error {
            color: red;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="create-product-container">
        <h1>Update Product</h1>

        <form action="update.php?id=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data" class="create-product-form">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo $product['description']; ?></textarea>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="<?php echo $product['price']; ?>" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?php echo $product['category']; ?>" required>

            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image">

            <button type="submit" name="update">Update Product</button>
        </form>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
