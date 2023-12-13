<?php
include 'navbar.php';
include 'db_config.php';

$sql = "SELECT product_id, name, description, price FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Produselor</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <h2>Produsele Noastre</h2>
        <div class="product-grid">
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>Preț: <?= htmlspecialchars($product['price']) ?> Lei</p>
                    <a href="add_to_cart.php?product_id=<?= $product['product_id'] ?>&quantity=1" class="btn btn-primary">Adaugă în Coș</a>
                </div>
            <?php endwhile; ?>
        </div>
        
        <?php
        // Verificați dacă există un mesaj de succes în sesiune
        if(isset($_SESSION['cart_success']) && $_SESSION['cart_success'] === true) {
            echo '<div class="success-message" id="success-message">Produsul a fost adăugat cu succes în coș!</div>';
            // Ștergeți variabila de sesiune pentru a evita afișarea repetată a mesajului.
            unset($_SESSION['cart_success']);
        }
        ?>
        
    </div>
    
    <script>
        // Așteaptă 5 secunde și apoi ascunde mesajul de succes
        setTimeout(function() {
            var successMessage = document.getElementById("success-message");
            if (successMessage) {
                successMessage.style.display = "none";
            }
        }, 5000); // 5000 milisecunde = 5 secunde
    </script>
</body>
</html>
