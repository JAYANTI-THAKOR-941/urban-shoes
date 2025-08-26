<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Home - Urban Shoes</title>
    <style>
             /* Hero Section Styles */
            .hero {
                height: 80vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(to bottom right, rgba(45, 31, 28, 0.8), rgba(51, 51, 51, 0.8)), url('https://urban-road.neto.com.au/assets/images/Topbanner%202.jpg') no-repeat center center/cover;
                color: #fff;
                text-align: center;
            }

            .hero-container h1 {
                font-size: 50px;
                margin: 0;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            }

            .hero-container p {
                font-size: 18px;
                margin: 20px 0;
            }

            .btn-shop-now {
                text-decoration: none;
                padding: 12px 25px;
                background-color: #f04e31;
                color: #fff;
                font-size: 18px;
                border-radius: 5px;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .btn-shop-now:hover {
                background-color: #ff623d;
            }



            /* Why Choose Us Section */
            .why-choose-us-section {
                background-color: #fff;
                padding: 50px 0;
            }

            .why-container {
                width: 80%;
                margin: 0 auto;
                text-align: center;
            }

            .why-container h2 {
                font-size: 2.5rem;
                margin-bottom: 20px;
                color: #333;
            }

            .why-container p {
                font-size: 1.2rem;
                margin-bottom: 30px;
                color: #666;
            }

            .why-list {
                list-style: none;
                padding: 0;
            }

            .why-list li {
                font-size: 1.1rem;
                margin: 15px 0;
                line-height: 1.8;
                color: #333;
            }

            .why-list li strong {
                color: #ff6f61;
            }

            /* Core Values Section */
            .core-values-section {
                background-color: #f9f9f9;
                padding: 50px 0;
            }

            .core-container {
                width: 80%;
                margin: 0 auto;
                text-align: center;
            }

            .core-container h2 {
                font-size: 2.5rem;
                margin-bottom: 20px;
                color: #333;
            }

            .core-container p {
                font-size: 1.2rem;
                margin-bottom: 30px;
                color: #666;
            }

            .core-list {
                list-style: none;
                padding: 0;
            }

            .core-list li {
                font-size: 1.1rem;
                margin: 15px 0;
                line-height: 1.8;
                color: #333;
            }

            .core-list li strong {
                color: #ff6f61;
            }



    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <h1>Welcome to Urban Shoes</h1>
                <p>Discover the latest trends and comfort in footwear.</p>
                <a href="/urban-shoes/pages/shop.php" class="btn-shop-now">Shop Now</a>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="why-choose-us-section">
            <div class="why-container">
                <h2>Why Choose Us?</h2>
                <p>At Urban Shoes, we believe in providing our customers with the best footwear that combines style, quality, and comfort. Here’s why we stand out:</p>
                <ul class="why-list">
                    <li><strong>Quality Products:</strong> We source only premium materials to ensure durability and comfort.</li>
                    <li><strong>Affordable Prices:</strong> Stylish footwear doesn’t have to break the bank.</li>
                    <li><strong>Customer Satisfaction:</strong> Your happiness is our priority, and we strive to exceed your expectations.</li>
                    <li><strong>Eco-Friendly Practices:</strong> We care for the environment as much as we care for your feet.</li>
                </ul>
            </div>
        </section>

        <!-- Core Values Section -->
        <section class="core-values-section">
            <div class="core-container">
                <h2>Our Core Values</h2>
                <p>Urban Shoes is built on a foundation of strong values that guide everything we do:</p>
                <ul class="core-list">
                    <li><strong>Integrity:</strong> We are committed to honesty and transparency in all our dealings.</li>
                    <li><strong>Innovation:</strong> Continuously improving and staying ahead of the trends in footwear design.</li>
                    <li><strong>Customer-Centric:</strong> Putting our customers at the heart of every decision.</li>
                    <li><strong>Sustainability:</strong> Promoting eco-friendly practices to reduce our environmental footprint.</li>
                </ul>
            </div>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>
</body>
</html>
