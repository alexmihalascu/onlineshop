<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

$sql = "SELECT product_id, name, description, price FROM products ORDER BY name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Produselor</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h2>Produsele Noastre</h2>

        <!-- Afișează aici mesajele de succes sau eroare -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <div class="product-grid">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>Preț: <?= htmlspecialchars($product['price']) ?> Lei</p>
                    <?php if ($loggedIn): ?>
                        <a href="add_to_cart.php?product_id=<?= $product['product_id'] ?>&quantity=1" class="btn btn-primary">Adaugă în Coș</a>
                    <?php else: ?>
                        <button class="btn btn-secondary disabled">Necesită autentificare</button>
                    <?php endif; ?>
                    <?php if ($isAdmin): ?>
                        <a href="delete_product.php?id=<?= $product['product_id'] ?>" class="btn btn-danger">Șterge Produs</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <?php
        // Verificați dacă există un mesaj de succes în sesiune
        if (isset($_SESSION['cart_success']) && $_SESSION['cart_success'] === true) {
            echo '<div class="success-message" id="success-message">Produsul a fost adăugat cu succes în coș!</div>';
            // Ștergeți variabila de sesiune pentru a evita afișarea repetată a mesajului.
            unset($_SESSION['cart_success']);
        }
        ?>

    </div>

    <script>
        // Așteaptă o secunda și apoi ascunde mesajul de succes
        setTimeout(function() {
            var successMessage = document.getElementById("success-message");
            if (successMessage) {
                successMessage.style.display = "none";
            }
        }, 1000); // 1000 milisecunde = 1 secunde
    </script>
</body>

</html>