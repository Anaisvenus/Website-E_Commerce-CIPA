<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Use an empty string if the root user has no password
$database = 'dashboard_management';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $message = "Email ID already exists";
        $toastClass = "bg-blue-500"; // Primary color
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (firstname, name, phone_number, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $name, $phone_number, $email, $password);

        if ($stmt->execute()) {
            // Redirect to index.html on success
            header("Location: ../index.html");
            exit();
        } else {
            // Redirect to register.html with a generic error message
            header("Location: register.html?message=Error%20creating%20account&status=error");
            exit();
        }

        $stmt->close();

        $stmt->close();
    }

    $checkEmailStmt->close();
    $conn->close();
}
?>
