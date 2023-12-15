<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$loggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Preia criteriul și direcția de sortare din sesiune sau setează unul implicit
$sort = isset($_SESSION['sort']) ? $_SESSION['sort'] : 'name';
$order = isset($_SESSION['order']) ? $_SESSION['order'] : 'ASC';

// Preia criteriile de sortare din GET, dacă sunt setate
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    $_SESSION['sort'] = $sort;
}
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    $_SESSION['order'] = $order;
}

// Validare și pregătire a query-ului pentru sortare
$allowedSorts = ['name', 'price', 'product_id'];
$orderBy = in_array($sort, $allowedSorts) ? $sort : 'name';

$sql = "SELECT product_id, name, description, price, stock FROM products ORDER BY $orderBy $order";
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
        <h2 class="mt-5">Produsele Noastre</h2>

        <!-- Formular pentru sortare -->
        <form action="product_list.php" method="get" class="mb-3">
            Sortează după:
            <select name="sort" onchange="this.form.submit()" class="form-select">
                <option value="name" <?= $sort == 'name' ? 'selected' : '' ?>>Nume</option>
                <option value="price" <?= $sort == 'price' ? 'selected' : '' ?>>Preț</option>
                <option value="product_id" <?= $sort == 'product_id' ? 'selected' : '' ?>>Data adăugării</option>
            </select>
            <select name="order" onchange="this.form.submit()" class="form-select">
                <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascendent</option>
                <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descendent</option>
            </select>
        </form>

        <div class="product-grid">
            <?php while ($product = $result->fetch_assoc()) : ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>Preț: <?= htmlspecialchars($product['price']) ?> Lei</p>
                    <?php if ($isAdmin) : ?>
                        <p>Stoc: <?= htmlspecialchars($product['stock']) ?></p>
                    <?php endif; ?>
                    <div class="action-buttons">
                        <?php if ($loggedIn) : ?>
                            <?php if ($product['stock'] > 0) : ?>
                                <a href="add_to_cart.php?product_id=<?= $product['product_id'] ?>&quantity=1" class="btn btn-primary">Adaugă în Coș</a>
                            <?php else : ?>
                                <button class="btn btn-secondary disabled">Stoc epuizat</button>
                            <?php endif; ?>
                        <?php else : ?>
                            <button class="btn btn-secondary disabled">Necesită autentificare</button>
                        <?php endif; ?>
                        <?php if ($isAdmin) : ?>
                            <a href="edit_product.php?id=<?= $product['product_id'] ?>" class="btn btn-primary">Editează Produs</a>
                            <a href="delete_product.php?id=<?= $product['product_id'] ?>" class="btn btn-danger">Șterge Produs</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>