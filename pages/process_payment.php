<?php
// Start session
session_start();

// Include Razorpay API library
require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

// Your Razorpay Key Secret
$keyId = getenv('RAZORPAY_KEY_ID');
$keySecret = getenv('RAZORPAY_KEY_SECRET');
if (!$keyId || !$keySecret) {
    http_response_code(503);
    exit('Payment service unavailable');
}

// Retrieve payment details sent via AJAX
$paymentId = $_POST['payment_id'];
$orderId = $_POST['order_id'];
$signature = $_POST['signature'];

// Initialize Razorpay API
$api = new Api($keyId, $keySecret);

// Verify payment signature
$attributes = array(
    'razorpay_order_id' => $orderId,
    'razorpay_payment_id' => $paymentId,
    'razorpay_signature' => $signature
);

try {
    // Verify the payment
    $api->utility->verifyPaymentSignature($attributes);

    // Redirect to success page
    echo 'Payment verification successful!';
} catch (\Exception $e) {
    // Handle failure (e.g., payment not verified)
    echo 'Payment verification failed';
}
?>
