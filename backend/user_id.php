<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_process.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['id'];

// Connect to the database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dashboard_management';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch user information
$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_email);
$stmt->fetch();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user_email); ?></h1>
    <p>Your account information is displayed here.</p>
</body>
</html>
