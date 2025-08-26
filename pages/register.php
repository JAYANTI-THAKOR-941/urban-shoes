<?php
include('../config/db_connection.php');
require '../vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required!";
    } else {
        // Check if user already exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "This email is already registered!";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Send OTP to email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // SMTP settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jyantithakor941@gmail.com'; 
                $mail->Password = 'mmwpvwpqrshtfrdy'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('jyantithakor941@gmail.com', 'Urban Shoes');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Registration';
                $mail->Body = "Dear $username,<br><br>Your OTP for registration is: <b>$otp</b><br><br>Thank you!";

                $mail->send();

                // Store OTP and user data in session
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);
                $_SESSION['otp'] = $otp;

                // Redirect to OTP verification page
                header('Location: otp_verification.php');
                exit;
            } catch (Exception $e) {
                $error_message = "Error sending OTP: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Register - Urban Shoes</title>
</head>
<body>
    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- Registration Form Section -->
    <section class="auth-section">
        <div class="container">
            <h2>Register</h2>

            <!-- Display error message if any -->
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <button type="submit" class="btn">Register</button>
                </div>
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </form>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
