<?php
include('../config/db_connection.php'); 
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
$stmt = mysqli_prepare($conn, $sql);

$searchParam = "%" . $searchQuery . "%";

// Bind parameters (string type for both)
mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Fetch all results
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Search Results - Urban Shoes</title>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main>
        <section class="search-results">
            <div class="container">
                <h2>Search Results for: "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
                
                <?php if (count($products) > 0): ?>
                    <div class="products-list">
                        <?php foreach ($products as $product): ?>
                            <div class="product-item">
                                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                <h3><?php echo $product['name']; ?></h3>
                                <p><?php echo $product['description']; ?></p>
                                <span><?php echo "$" . number_format($product['price'], 2); ?></span>
                                <a href="/urban-shoes/pages/product.php?id=<?php echo $product['id']; ?>" class="btn-view">View Product</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No products found for your search query.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
