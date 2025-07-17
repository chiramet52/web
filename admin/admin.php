<?php
session_start();
require '../php/db.php';

// Check if the user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Fetch admin user details
$username = $_SESSION['admin'];
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <h2>Welcome to the Admin Dashboard, <?= htmlspecialchars($user['username']) ?></h2>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Manage Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <h3>Overview</h3>
    <p>This is the main admin panel where you can manage various aspects of the application.</p>
    <!-- Additional dashboard content can be added here -->
</body>
</html>