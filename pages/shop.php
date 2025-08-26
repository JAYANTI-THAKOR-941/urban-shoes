<?php
session_start();

// Database connection
include('../config/db_connection.php');

// Query for categories
$categoryQuery = "SELECT DISTINCT category FROM products";
$categoryResult = mysqli_query($conn, $categoryQuery);

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;

// Base query for products
$query = "SELECT p.id, p.name, p.description, p.price, pi.image_url 
          FROM products p 
          LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1 
          WHERE 1";

// Apply filters
if ($selectedCategory) {
    $query .= " AND p.category = '" . mysqli_real_escape_string($conn, $selectedCategory) . "'";
}

if ($searchQuery) {
    $query .= " AND (p.name LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%' OR p.description LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%')";
}

if ($minPrice > 0) {
    $query .= " AND p.price >= $minPrice";
}

if ($maxPrice > 0) {
    $query .= " AND p.price <= $maxPrice";
}

// Fetch filtered products
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products - Urban Shoes</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }

        /* Search bar */
        .search-bar {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .search-box {
            width: 80%;
            max-width: 600px;
            display: flex;
            border-radius: 50px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        .search-box button {
            padding: 12px 15px;
            background-color: #f04e31;
            border: none;
            cursor: pointer;
            color: #fff;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .search-box button:hover {
            background-color: #e14e2d;
        }

        .product-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .filter-sidebar {
            width: 20%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-sidebar h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        .filter-sidebar form {
            display: flex;
            flex-direction: column;
        }

        .filter-sidebar select, .filter-sidebar input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .filter-sidebar button {
            padding: 10px;
            background-color: #f04e31;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .filter-sidebar button:hover {
            background-color: #e14e2d;
        }

        /* Product Grid */
        .product-grid {
            width: 75%;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .product-card {
            padding: 0 0 20px 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-card .details {
            padding: 10px 15px;
            text-align: center;
        }

        .product-card .details h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .product-card .details p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .product-card .details .price {
            font-size: 16px;
            color: #f04e31;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .product-card .details .btn {
            padding: 10px 20px;
            background-color: #f04e31;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .product-card .details .btn:hover {
            background-color: #e14e2d;
        }

        @media (max-width: 768px) {
            .product-container {
                flex-direction: column;
                align-items: center;
            }

            .filter-sidebar {
                width: 100%;
                margin-bottom: 20px;
            }

            .product-grid {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include('../includes/header.php'); ?>

    <!-- Main Title -->
    <h1>Explore Our Latest Products</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form action="" method="GET" class="search-box">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search for products..." />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Product Container -->
    <div class="product-container">
        <!-- Filter Sidebar -->
        <div class="filter-sidebar">
    <h2>Filters</h2>
    <form action="" method="GET">
        <!-- Category Filter -->
        <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while ($category = mysqli_fetch_assoc($categoryResult)): ?>
                <option value="<?php echo $category['category']; ?>" <?php echo ($selectedCategory === $category['category']) ? 'selected' : ''; ?>>
                    <?php echo $category['category']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Price Filter -->
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $minPrice; ?>">
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $maxPrice; ?>">

        <button type="submit">Apply Filters</button>
    </form>

    <!-- Clear Filters Button -->
    <form action="" method="GET">
        <button type="submit" style="margin-top: 10px; background-color: #ccc; color: #333;">Clear Filters</button>
    </form>
</div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <img src="../assets/images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
                        <div class="details">
                            <h3><?php echo $product['name']; ?></h3>
                            <p><?php echo substr($product['description'], 0, 100); ?>...</p>
                            <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                            <a href="description.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
