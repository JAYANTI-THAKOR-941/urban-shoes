<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Urban Shoes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background-color: #fff;
            padding: 50px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            font-size: 32px;
            color: #27ae60;
            margin-bottom: 20px;
        }

        .success-icon {
            font-size: 80px;
            color: #27ae60;
            margin-bottom: 30px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .home-btn {
            display: inline-block;
            padding: 10px 40px;
            font-size: 18px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }

        .home-btn:hover {
            background-color: #2980b9;
        }

        .home-btn i {
            margin-right: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            h1 {
                font-size: 28px;
            }

            .success-icon {
                font-size: 60px;
            }

            .home-btn {
                font-size: 16px;
                padding: 12px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Order Successful!</h1>
        <p>Thank you for your purchase. Your order has been confirmed. We're processing your order now!</p>
        <a href="/urban-shoes/index.php" class="home-btn">
            <i class="fas fa-home"></i> Go to Home
        </a>
    </div>
</body>

</html>
