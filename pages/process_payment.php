<?php
// Start session
session_start();

// Include Razorpay API library
require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

// Your Razorpay Key Secret
$keySecret = 'hTSHYNC8Cm084lPqg9AdejH7';

// Retrieve payment details sent via AJAX
$paymentId = $_POST['payment_id'];
$orderId = $_POST['order_id'];
$signature = $_POST['signature'];

// Initialize Razorpay API
$api = new Api('rzp_test_MCCHlSWeh3mRj4', $keySecret);

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
    echo 'Payment verification failed: ' . $e->getMessage();
}
?>
