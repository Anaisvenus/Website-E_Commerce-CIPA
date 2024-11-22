<?php
// Connexion à la base de données
$host = 'localhost';  // Adresse du serveur
$dbname = 'dashboard_management';  // Nom de la base de données
$username = 'root';  // Nom d'utilisateur de la base de données
$password = '';  // Mot de passe de la base de données

$pdo = null;  // Initialiser la variable $pdo

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Afficher l'erreur si la connexion échoue
    echo "Connection failed: " . $e->getMessage();
    exit;  // Arrêter l'exécution si la connexion échoue
}

// Traitement de la recherche
$searchTerm = '';
$category = '';
$products = [];

// Récupérer les catégories depuis la base de données (supposant qu'il existe une table "categories")
$categories = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT id, nom FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    if ($pdo) {
        // Construire la requête SQL pour filtrer par nom et catégorie
        $sql = "SELECT * FROM produits WHERE nom LIKE :searchTerm";
        
        // Ajouter la condition pour la catégorie si elle est spécifiée
        if ($category) {
            $sql .= " AND categorie_id = :category";
        }
        
        $stmt = $pdo->prepare($sql);
        $params = ['searchTerm' => '%' . $searchTerm . '%'];
        
        // Ajouter le paramètre de catégorie si nécessaire
        if ($category) {
            $params['category'] = $category;
        }

        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Produits</title>
     <!-- Link fontawesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- slider stylesheet -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

<!-- bootstrap core css -->
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />

<!-- Custom styles for this template -->
<link href="../css/style.css" rel="stylesheet" />
<!-- responsive style -->
<link href="../css/responsive.css" rel="stylesheet" />
<link href="../css/shop.css" rel="stylesheet" />

<link href="dist/output.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        .search-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"], select {
            width: 60%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 60%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        h2 {
            color: #333;
        }

        .product-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .product-list li {
            background-color: white;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .product-list li strong {
            color: #4CAF50;
            font-size: 18px;
        }

        .product-list li p {
            font-size: 14px;
            color: #555;
        }

        .product-list li .price {
            font-weight: bold;
            color: #4CAF50;
        }

        .no-results {
            text-align: center;
            font-size: 16px;
            color: #ff5722;
        }
    </style>
</head>
<body>
<header class="header_section">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.html">
          <span>
            CIPA JESSOUGNON
          </span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class=""></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav  ">
            <li class="nav-item active">
              <a class="nav-link" href="index.html">Accueil <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="shop.html">
                Nos produits
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="basket.html">
                Mon panier
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="testimonial.html">
                Avis
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contactez-nous</a>
            </li>
          </ul>
          <div class="user_option">
            <a href="basket.html">
              <i class="fa fa-shopping-bag" aria-hidden="true"></i>
            </a>
            <a href="../backend/search-results.php">
              <button class="btn nav_search-btn" type="submit">
                <i class="fa fa-search" aria-hidden="true"></i>
              </button>
            </a>
            <a href="login.html">Se connecter</a>
          </div>
        </div>
      </nav>
    </header>

    <h1>Recherche de Produits</h1>

    <!-- Formulaire de recherche -->
    <div class="search-container">
        <form method="get" action="">
            <label for="search">Rechercher par nom :</label>
            <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchTerm) ?>" required>
            
            <label for="category">Catégorie :</label>
            <select name="category" id="category">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <?php if ($searchTerm): ?>
        <h2>Résultats pour : "<?= htmlspecialchars($searchTerm) ?>"</h2>

        <?php if (count($products) > 0): ?>
            <ul class="product-list">
                <?php foreach ($products as $product): ?>
                    <li>
                        <strong>
                            <?= isset($product['nom']) ? htmlspecialchars($product['nom']) : 'Nom non disponible' ?>
                        </strong><br>
                        Description: 
                        <?= isset($product['description']) ? htmlspecialchars($product['description']) : 'Description non disponible' ?><br>
                        Prix: 
                        <?= isset($product['prix_unitaire']) ? htmlspecialchars($product['prix_unitaire']) : 'Prix non disponible' ?> F CFA
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-results">Aucun produit trouvé.</p>
        <?php endif; ?>
    <?php endif; ?>

    
  <section class="info_section layout_padding2-top">
    <div class="info_container">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-3">
            <h6>CIPA JESSOUGNON</h6>
            <p>Nous sommes une entreprise de nettoyage professionnelle dédiée à fournir des services de haute qualité.</p>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="info_form">
              <h5>Newsletter</h5>
              <form action="#">
                <input type="email" placeholder="Entrez votre email">
                <button>Abonnez-vous</button>
              </form>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <h6>Besoin d'aide?</h6>
            <p>Contactez-nous au <a href="tel:+229 62 31 5172">+229 62 31 5172</a></p>
          </div>
          <div class="col-md-6 col-lg-3">
            <h6>CONTACTTEZ-NOUS</h6>
            <p><i class="fa fa-map-marker"></i> 9F74+4QM, Rue 1682B, Cotonou</p>
            <p><i class="fa fa-phone"></i> +229 62 31 5172</p>
            <p><i class="fa fa-envelope"></i> jessougnoncipa2@yahoo.fr</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer_section">
    <div class="container">
      <p>&copy; <span id="displayYear"></span> Tous droits réservés par <a href="https://html.design/">Free Html Templates</a></p>
    </div>
  </footer>
  <!-- end info section -->


  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <script src="js/custom.js"></script>

</body>
</html>
