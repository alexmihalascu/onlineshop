<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Adaugă produsul în baza de date
    $sql = "INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$name, $description, $price, $stock])) {
        echo "<p>Produsul a fost adăugat cu succes!</p>";
    } else {
        $error = "A apărut o eroare la adăugarea produsului.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adăugare Produs Nou</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Adaugă un Produs Nou</h2>
        <?php if ($error): ?>
            <p class="text-danger"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="add_product.php">
            <div class="mb-3">
                <label for="name" class="form-label">Nume Produs:</label>
                <input type="text" id="name" name="name" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descriere:</label>
                <textarea id="description" name="description" required class="form-control no-resize"></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Preț:</label>
                <input type="number" id="price" name="price" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stoc:</label>
                <input type="number" id="stock" name="stock" required class="form-control">
            <div class="mb-3">
                <input type="submit" value="Adaugă Produsul" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
