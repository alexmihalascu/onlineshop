<?php
include 'navbar.php';
include 'db_config.php';
session_start();

// Verifică dacă utilizatorul este admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$product_id = isset($_GET['id']) ? $_GET['id'] : die('Produsul nu a fost găsit.');

// Preluarea datelor produsului pentru editare
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$name, $description, $price, $product_id])) {
        echo "<p>Produsul a fost actualizat cu succes!</p>";
    } else {
        echo "<p>A apărut o eroare la actualizarea produsului.</p>";
    }
} else {
    $sql = "SELECT name, description, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$product) {
    die('Produsul nu a fost găsit.');
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Produs</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Editare Produs</h2>
        <form method="post" action="edit_product.php?id=<?= htmlspecialchars($product_id) ?>">
            <div class="form-group">
                <label>Nume Produs:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Descriere:</label>
                <textarea name="description" required class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Preț:</label>
                <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizează Produsul" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
