<?php
include('../config/db_connection.php');

// Fetch all products from the database
$query = "SELECT p.id, p.name, p.description, p.price, p.category, pi.image_url 
          FROM products p 
          LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Dashboard - Urban Shoes</title>
    <style>
        .dashboard-container {
            width: 90%;
            margin: 50px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            color: #333;
            font-size: 32px;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .product-table th,
        .product-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .product-table th {
            background-color: #f04e31;
            color: white;
        }

        .product-table td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-table td .actions a, .add-btn,.back-btn {
            padding: 8px 15px;
            margin: 5px;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-btn{
            background-color: #234a21;
            
        }
        .product-table td .actions a.edit {
            background-color: #f04e31;
        }

        .product-table td .actions a.delete {
            background-color: #dc3545;
        }

        .product-table td .actions a:hover {
            background-color: #f04e31;
        }

        .no-products {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a>
        <br><br>
        <a href="createProduct.php" class="add-btn">Add New Product</a> 
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="../assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars(substr($product['description'], 0, 70)); ?>...</td>
                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td class="actions">
                                <a href="update.php?id=<?php echo $product['id']; ?>" class="edit">Edit</a>
                                <a href="delete.php?id=<?php echo $product['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-products">No products found in the database.</p>
        <?php endif; ?>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
