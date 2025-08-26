<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('../config/db_connection.php');

$user_id = $_SESSION['user_id'];

// Fetch user details to check if profile is completed
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found");
}

// Check if the profile is already completed
if ($user['phone'] && $user['address'] && $user['profile_image']) {
    header("Location: account.php"); // Redirect to account page if the profile is complete
    exit();
}

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['date_of_birth'];
    $profile_image = $_FILES['profile_image']['name'];

    // Handle file upload for profile image
    if (!empty($profile_image)) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/urban-shoes/uploads/"; // Absolute path
        $target_file = $target_dir . basename($profile_image);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the file type
        if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Validate file size (max 5MB)
        if ($_FILES['profile_image']['size'] > 5000000) {
            die("Sorry, your file is too large. Maximum file size is 5MB.");
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            die("Error uploading the profile image.");
        }
    } else {
        $target_file = $user['profile_image']; // Use the existing image if no new image is uploaded
    }

    // Update user details in the database
    $update_query = "UPDATE users SET phone = ?, address = ?, date_of_birth = ?, profile_image = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $phone, $address, $dob, $target_file, $user_id);

    if ($stmt->execute()) {
        // Fetch the updated user details
        $updated_query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($updated_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_user = $result->fetch_assoc();

        // Set updated user data in the session
        $_SESSION['user_id'] = $updated_user['user_id'];
        $_SESSION['phone'] = $updated_user['phone'];
        $_SESSION['address'] = $updated_user['address'];
        $_SESSION['date_of_birth'] = $updated_user['date_of_birth'];
        $_SESSION['profile_image'] = $updated_user['profile_image'];

        // Redirect after successful update
        header("Location: account.php");
        exit();
    } else {
        die("Error updating profile: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .complete-profile-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .complete-profile-container h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .complete-profile-container label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        .complete-profile-container input, .complete-profile-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .complete-profile-container button {
            width: 100%;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .complete-profile-container button:hover {
            background-color: #0056b3;
        }

        .complete-profile-container input[type="file"] {
            padding: 5px;
        }
    </style>
</head>
<body>

    <?php include('../includes/header.php'); ?>

    <div class="complete-profile-container">
        <h1>Complete Your Profile</h1>
        <form action="complete-profile.php" method="POST" enctype="multipart/form-data">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>

            <label>Address:</label>
            <textarea name="address" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>

            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>" required>

            <label>Profile Image:</label>
            <input type="file" name="profile_image">

            <button type="submit">Complete Profile</button>
        </form>
    </div>

    <?php include('../includes/footer.php'); ?>

</body>
</html>
