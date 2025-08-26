<?php
// Start output buffering to avoid header issues
ob_start();
include('../config/db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Add New Product - Urban Shoes</title>
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
            background-color: #d73a22;
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
        <h1>Create Product</h1>
        <form class="create-product-form" method="POST" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="name" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>

            <label>Category:</label>
            <input type="text" name="category" required>

            <label>Images (Max 5):</label>
            <input type="file" name="images[]" multiple accept="image/*" required>

            <button type="submit" name="submit">Create Product</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            // Prepare statement for inserting product details
            $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, category) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $category);

            if (mysqli_stmt_execute($stmt)) {
                $product_id = mysqli_insert_id($conn); // Get the inserted product ID

                // Handle image uploads
                $images = $_FILES['images'];
                $upload_directory = "../assets/images/";
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

                foreach ($images['name'] as $index => $image_name) {
                    $image_tmp_name = $images['tmp_name'][$index];
                    $image_type = $images['type'][$index];
                    $image_error = $images['error'][$index];

                    if ($image_error === 0 && in_array($image_type, $allowed_types)) {
                        $target = $upload_directory . basename($image_name);

                        if (move_uploaded_file($image_tmp_name, $target)) {
                            // Insert image details into the product_images table
                            $is_main = $index === 0 ? 1 : 0; // Set first image as the main image
                            $stmt_image = mysqli_prepare($conn, "INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, ?)");
                            mysqli_stmt_bind_param($stmt_image, "isi", $product_id, $image_name, $is_main);
                            mysqli_stmt_execute($stmt_image);
                            mysqli_stmt_close($stmt_image);
                        } else {
                            echo "<p class='create-product-message error'>Failed to upload image: $image_name</p>";
                        }
                    } else {
                        echo "<p class='create-product-message error'>Invalid file type for image: $image_name</p>";
                    }
                }

                // Redirect after success
                header("Location: product_dashboard.php");
                exit;
            } else {
                echo "<p class='create-product-message error'>Error: " . mysqli_error($conn) . "</p>";
            }

            mysqli_stmt_close($stmt);
        }
        ?>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>

<?php
// End output buffering
ob_end_flush();
?>
