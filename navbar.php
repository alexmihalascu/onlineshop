<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Magazin Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if ($loggedIn) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="product_list.php">Produse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Coș</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user_profile.php">Profil</a>
                        </li>
                        <?php if ($isAdmin) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="add_product.php">Adaugă Produs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage_users.php">Gestionare Utilizatori</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="manage_orders.php">Gestionare Comenzi</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="my_orders.php">Comenzile mele</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if ($loggedIn) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Deconectare</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>
