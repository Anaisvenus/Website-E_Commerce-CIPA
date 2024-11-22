<?php
include 'db_connect.php'; // Connexion à la base de données

$sql = "SELECT id, nom FROM categories";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

echo json_encode($categories);
?>
