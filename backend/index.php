<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dashboard_management';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Fetch products
$sql = "SELECT produit_id, nom, prix_unitaire, description, image_url FROM produits";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['image_url'] = empty($row['image_url']) ? 'images/default-image.jpg' : $row['image_url'];
        $products[] = $row;
    }
}

$conn->close();
echo json_encode($products);
?>
