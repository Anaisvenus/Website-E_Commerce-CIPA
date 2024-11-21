<?php
// Include the database connection (adjust the path to where your database connection is located)
include('index.php');  // Make sure the path is correct

// Get the product ID from the URL parameter
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($product_id) {
    // Query the database to get the details of the product with the given ID
    $query = "SELECT * FROM produits WHERE id = $product_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Produit introuvable.";
    }
} else {
    $error_message = "ID du produit manquant.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($product) ? $product['nom'] : 'Détails du produit'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <?php if (isset($product)): ?>
            <!-- Product Detail Section -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="font-bold text-3xl mb-4"><?php echo $product['nom']; ?></h1>
                <img src="images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['nom']; ?>" class="w-full h-auto rounded-lg mb-4">
                <p class="text-sm text-gray-500 mb-4"><?php echo $product['description']; ?></p>
                <p class="font-semibold text-green-600 text-xl mb-4"><?php echo $product['prix']; ?> FCFA</p>
                
                <!-- Quantity Selector and Add to Cart Button -->
                <div class="mt-4 flex items-center justify-between">
                    <label for="quantity" class="text-sm">Quantité :</label>
                    <input class="w-16 p-2 border rounded-lg text-sm" id="quantity" type="number" value="1" min="1">
                    <button class="bg-blue-500 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-600 mt-4" onclick="addToCart('<?php echo $product['nom']; ?>', <?php echo $product['prix']; ?>, 'quantity')">
                        Ajouter au panier
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Error Message if Product is Not Found -->
            <p class="text-center text-red-500 font-semibold"><?php echo isset($error_message) ? $error_message : 'Produit non trouvé'; ?></p>
        <?php endif; ?>
    </div>

    <script>
        function addToCart(productName, price, quantityId) {
            const quantity = parseInt(document.getElementById(quantityId).value);
            
            // Check if quantity is a valid number and greater than 0
            if (quantity <= 0 || isNaN(quantity)) {
                alert("Veuillez saisir une quantité valide.");
                return;
            }

            const product = {
                name: productName,
                price: price,
                quantity: quantity,
                image: "images/<?php echo $product['image_url']; ?>"
            };

            // Retrieve existing cart items from localStorage
            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            // Check if the product already exists in the cart
            const index = cart.findIndex(item => item.name === product.name);

            if (index > -1) {
                // If the product exists, increase the quantity
                cart[index].quantity += product.quantity;
            } else {
                // If the product doesn't exist, add it to the cart
                cart.push(product);
            }

            // Save the updated cart in localStorage
            localStorage.setItem("cart", JSON.stringify(cart));

            alert("Produit ajouté au panier !");
        }
    </script>
</body>
</html>
