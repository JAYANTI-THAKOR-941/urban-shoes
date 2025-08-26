<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('../config/db_connection.php');

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching user details: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

// Set session variables for user details
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];
$_SESSION['phone'] = $user['phone'];
$_SESSION['address'] = $user['address'];
$_SESSION['dob'] = $user['date_of_birth'];
$_SESSION['profile_image'] = $user['profile_image'];

// Update user profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    // Handle profile image upload
    $profile_image = $user['profile_image'];
    if ($_FILES['profile_image']['name']) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/urban-shoes/uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image = $target_file;
        }
    }

    $update_query = "UPDATE users SET username = '$username', email = '$email', phone = '$phone', address = '$address', date_of_birth = '$dob', profile_image = '$profile_image' WHERE user_id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        // Update session variables after successful profile update
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['address'] = $address;
        $_SESSION['dob'] = $dob;
        $_SESSION['profile_image'] = $profile_image;

        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: account.php");
        exit();
    } else {
        $error_message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>

        /* Profile Update Form Container */
        .profile-form-container {
            width: 60%;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Form label and input styles */
        label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Button for submitting form */
        .form-btn {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form-btn:hover {
            background-color: #0056b3;
        }

        /* Profile image preview styling */
        .profile-img-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        /* Error message */
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Success message */
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Include header -->
    <?php include('../includes/header.php'); ?>

    <!-- Profile Update Form -->
    <div class="profile-form-container">
        <h1>Update Your Profile</h1>

        <!-- Display success or error message -->
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php elseif (isset($_SESSION['message'])): ?>
            <p class="success-message"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="POST" action="update-profile.php" enctype="multipart/form-data">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required>

            <label for="profile_image">Profile Image</label>
            <input type="file" id="profile_image" name="profile_image">

            <?php if ($user['profile_image']): ?>
                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-img-preview">
            <?php endif; ?>

            <button type="submit" class="form-btn">Update Profile</button>
        </form>
    </div>

    <!-- Include footer -->
    <?php include('../includes/footer.php'); ?>

</body>
</html>
