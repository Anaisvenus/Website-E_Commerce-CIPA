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

// Function to fetch product by ID
function getProductById($conn, $id) {
    $sql = "SELECT produit_id, nom, prix_unitaire, description, image_url FROM produits WHERE produit_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product['image_url'] = empty($product['image_url']) ? '../images/default-image.jpg' : '../' . $product['image_url'];
        return $product;
    } else {
        return null;
    }
}


// Function to fetch all products
function getAllProducts($conn) {
    $sql = "SELECT produit_id, nom, prix_unitaire, description, image_url FROM produits";
    $result = $conn->query($sql);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['image_url'] = empty($row['image_url']) ? '../images/default-image.jpg' : '../' . $row['image_url'];
            $products[] = $row;
        }
    }
    return $products;
}


// Get the product ID from the query string if available
$productId = isset($_GET['id']) ? $_GET['id'] : null;

if ($productId) {
    // Fetch product details by ID
    $product = getProductById($conn, $productId);
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    // If no ID is specified, return all products
    $products = getAllProducts($conn);
    echo json_encode($products);
}

// Close the database connection
$conn->close();
?>
