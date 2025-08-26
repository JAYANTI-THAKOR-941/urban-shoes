

<?php
session_start();
include('../config/db_connection.php');

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Login</title>
    <style>
        .login-container {
            width: 25%;
            margin: 100px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
        }
        .login-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .login-container form input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-container form button {
            padding: 10px;
            background-color: #f04e31;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container form button:hover {
            background-color: #d93b29;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
