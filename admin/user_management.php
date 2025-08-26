
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

// Fetch all users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Check for any errors
if (!$result) {
    echo "Error fetching users: " . mysqli_error($conn);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>User Management - Admin Dashboard</title>
    <style>
        .dashboard-container {
            width: 90%;
            margin: 50px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            color: #333;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .user-table th,
        .user-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .user-table th {
            background-color: #f04e31;
            color: white;
        }

        .user-table td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .user-table td .actions a ,.back-btn{
            padding: 8px 15px;
            margin: 5px;
            text-decoration: none;
            background-color: #f04e31;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-btn{
            background-color: #234a21;
            
        }
        .user-table td .actions a.delete {
            background-color: #dc3545;
        }

        .user-table td .actions a:hover {
            background-color: #d93b29;
        }

        .no-users {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="dashboard-container">
        <h1>User Management</h1>
        <a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td class="actions">
                                <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-users">No users found in the database.</p>
        <?php endif; ?>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
