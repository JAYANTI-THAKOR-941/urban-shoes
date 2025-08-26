<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$errorMessage = '';
$successMessage = '';
$name = '';
$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate user inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jyantithakor941@gmail.com'; 
            $mail->Password = 'mmwpvwpqrshtfrdy'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('jyantithakor941@gmail.com'); 

            //Content
            $mail->isHTML(false);  
            $mail->Subject = 'Contact Us Form Submission';
            $mail->Body = "Name: $name\nEmail: $email\nMessage:\n$message";

            // Send email
            $mail->send();
            $successMessage = "Thank you for your message! We will get back to you shortly.";
            
            // Clear the form after successful submission
            $name = '';
            $email = '';
            $message = '';
        } catch (Exception $e) {
            $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
    <title>Contact Us - Urban Shoes</title>
    <script>
        // JavaScript to show the success or error message in a popup
        <?php if ($errorMessage) { ?>
            alert("Error: <?php echo $errorMessage; ?>");
        <?php } ?>
        <?php if ($successMessage) { ?>
            alert("Success: <?php echo $successMessage; ?>");
        <?php } ?>
    </script>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <!-- Contact Us Section -->
    <section class="contact-us">
        <div class="contact-container">
            <!-- Contact Information -->
            <div class="contact-info">
                <h2>Contact Information</h2>
                <p>We are always happy to hear from you! Reach out to us through any of the following means:</p>

                <div class="contact-item">
                    <img src="https://cdn-icons-png.freepik.com/512/2997/2997583.png" alt="Location Icon" />
                    <p>123 Urban Shoes Street, Shoe City, SC 12345</p>
                </div>
                <div class="contact-item">
                    <img src="https://cdn-icons-png.freepik.com/512/5610/5610987.png" alt="Phone Icon" />
                    <p>+1 (234) 567-890</p>
                </div>
                <div class="contact-item">
                    <img src="https://cdn-icons-png.freepik.com/512/724/724662.png" alt="Email Icon" />
                    <p>info@urbanshoes.com</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="contact.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="name" placeholder="Your Name" value="<?php echo $name; ?>" required />
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Your Email" value="<?php echo $email; ?>" required />
                    </div>
                    <div class="input-group">
                        <textarea name="message" placeholder="Your Message" rows="6" required><?php echo $message; ?></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>
    
    <?php include('../includes/footer.php'); ?>
</body>
</html>
