// Exemple de données du panier
const cartItems = [
    { name: "Produit A", quantity: 2, price: 15.50 },
    { name: "Produit B", quantity: 1, price: 22.00 },
    { name: "Produit C", quantity: 3, price: 7.99 }
];

// Affichage des articles dans le tableau
function displayCart() {
    const cartTableBody = document.getElementById('cartItems');
    let totalAmount = 0;

    cartItems.forEach(item => {
        const row = document.createElement('tr');
        
        // Affichage des informations dans chaque cellule du tableau
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.quantity}</td>
            <td>${item.price} €</td>
            <td>${(item.quantity * item.price).toFixed(2)} €</td>
        `;
        
        cartTableBody.appendChild(row);
        totalAmount += item.quantity * item.price;
    });

    // Affichage du montant total
    document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
}

// Appel de la fonction pour afficher les articles du panier
displayCart();

// Soumission du formulaire
document.getElementById('paymentForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission réelle du formulaire

    // Validation ou traitement des informations ici (par exemple, envoi vers un serveur)
    alert("Commande validée avec succès !");
});
