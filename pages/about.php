<?php
// Start the session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>About Us - Urban Shoes</title>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <!-- About Us Header Image Section -->
    <section class="about-header">
        <div class="about-header-content">
            <h1>About Urban Shoes</h1>
            <p>Your destination for style, comfort, and quality footwear.</p>
        </div>
    </section>

    <!-- About Us Main Content -->
    <section class="about-us">
        <div class="container">
            <h2>Our Story</h2>
            <p>Urban Shoes was founded with the goal of providing high-quality footwear that caters to all tastes and budgets. We understand that shoes are not just accessories but an essential part of everyday life, helping you make a statement wherever you go. From casual sneakers to elegant formal shoes, we offer a wide selection for every occasion.</p>

            <h2>Our Mission</h2>
            <p>At Urban Shoes, we are committed to delivering fashionable footwear that is comfortable and durable. We aim to create a shopping experience where quality meets affordability, ensuring that everyone can step out in style.</p>

            <h2>What Makes Us Different?</h2>
            <ul>
                <li><strong>Quality Assurance:</strong> Every pair of shoes we sell is crafted with care and precision, using the finest materials.</li>
                <li><strong>Customer-Centric Approach:</strong> We prioritize customer satisfaction, offering easy returns and exchanges.</li>
                <li><strong>Variety:</strong> We offer a wide range of styles to suit various tastes, from athletic to formal, casual to trendy.</li>
                <li><strong>Fast Delivery:</strong> Our efficient delivery system ensures that your orders reach you in no time.</li>
            </ul>

            <h2>Our Values</h2>
            <p>At the core of Urban Shoes are the values that guide our decisions and actions. We strive for excellence, transparency, and respect in all aspects of our business.</p>

            <h2>Join the Urban Shoes Family</h2>
            <p>We invite you to explore our collection and join thousands of satisfied customers who have found the perfect shoes for every occasion. With Urban Shoes, you're not just buying shoes; you're investing in comfort, style, and quality.</p>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
