<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Magazin Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if ($loggedIn) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Acasă</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="product_list.php">Produse</a>
                        </li>
                        <?php if ($isAdmin) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="add_product.php">Adaugă Produs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage_users.php">Gestionare Utilizatori</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage_orders.php">Comenzi</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Coș</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user_profile.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Deconectare</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (!$loggedIn) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Autentificare</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Înregistrare</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Restul conținutului paginii aici -->

</body>
</html>
