<?php
include('../config/db_connection.php'); // Update with the correct path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);

    // Validate if the product exists
    $productCheck = "SELECT * FROM products WHERE id = $productId";
    $productResult = mysqli_query($conn, $productCheck);
    if (mysqli_num_rows($productResult) === 0) {
        die("Invalid Product ID.");
    }

    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        $uploadDir = '../assets/images/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $imageTmp = $_FILES['images']['tmp_name'][$key];
            $imageType = $_FILES['images']['type'][$key];
            $imageError = $_FILES['images']['error'][$key];

            if ($imageError === 0 && in_array($imageType, $allowedTypes)) {
                $uniqueName = uniqid() . '-' . basename($imageName);
                $targetPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($imageTmp, $targetPath)) {
                    $query = "INSERT INTO product_images (product_id, image_path) VALUES ('$productId', '$uniqueName')";
                    if (!mysqli_query($conn, $query)) {
                        echo "Error saving image: " . mysqli_error($conn);
                    }
                } else {
                    echo "Failed to upload image: $imageName";
                }
            } else {
                echo "Invalid file type or error uploading file: $imageName";
            }
        }

        echo "Images uploaded successfully!";
    } else {
        echo "No images selected.";
    }
} else {
    echo "Invalid request.";
}
?>
