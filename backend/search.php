<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Use an empty string if the root username has no password
$database = 'dashboard_management';

try {
    $pdo = new PDO("mysql:host=$host; dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les paramètres de recherche
$query = isset($_GET['query']) ? '%' . $_GET['query'] . '%' : '%';
$category = isset($_GET['category']) && $_GET['category'] !== '' ? $_GET['category'] : null;

// Préparer la requête SQL
$sql = "SELECT * FROM products WHERE name LIKE :query";
if ($category) {
    $sql .= " AND category_id = :category";
}

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':query', $query, PDO::PARAM_STR);
if ($category) {
    $stmt->bindParam(':category', $category, PDO::PARAM_INT);
}

$stmt->execute();

// Récupérer les résultats
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les résultats sous forme de JSON
header('Content-Type: application/json');
echo json_encode($products);
?>
