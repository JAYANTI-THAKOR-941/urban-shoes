<?php
session_start();
include('../config/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $hashed_password = $_SESSION['password'];

        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Set user session as logged in
            $_SESSION['user_id'] = mysqli_insert_id($conn); 
            $_SESSION['username'] = $username;

            unset($_SESSION['otp']);
            unset($_SESSION['password']); 

            // Redirect to the home page
            header('Location: ../index.php');
            exit;
        } else {
            $error_message = "Error completing registration!";
        }
    } else {
        $error_message = "Invalid OTP! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Verify OTP - Urban Shoes</title>
</head>
<body>
    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- OTP Verification Section -->
    <section class="auth-section">
        <div class="container">
            <h2>Verify OTP</h2>

            <!-- Display error message if any -->
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="otp_verification.php" method="POST">
                <div class="input-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <div class="input-group">
                    <button type="submit" class="btn">Verify</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
