<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Fetch statistics or key metrics for the dashboard
$stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
$userCount = $stmt->fetch()['user_count'];

// Additional metrics can be fetched here

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <h2>Welcome to the Admin Dashboard</h2>
    <p>Total Users: <?= $userCount ?></p>
    <!-- Additional metrics can be displayed here -->

    <h3>Admin Panel Features</h3>
    <ul>
        <li><a href="users.php">Manage Users</a></li>
        <li><a href="logout.php">Logout</a></li>
        <!-- Add more features as needed -->
    </ul>
</body>
</html>