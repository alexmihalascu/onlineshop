<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$product_id = isset($_GET['id']) ? $_GET['id'] : die('Produsul nu a fost găsit.');

// Procesarea datelor formularului pentru editare
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Eroare la pregătirea interogării: " . $conn->error);
    }

    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $product_id);
    if (!$stmt->execute()) {
        die("Eroare la execuția interogării: " . $stmt->error);
    }

    echo "<p>Produsul a fost actualizat cu succes!</p>";
}

// Preluarea datelor produsului pentru editare
$sql = "SELECT name, description, price, stock FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Eroare la pregătirea interogării: " . $conn->error);
}

$stmt->bind_param("i", $product_id);
if (!$stmt->execute()) {
    die("Eroare la execuția interogării: " . $stmt->error);
}

$result = $stmt->get_result();
$product = $result->fetch_assoc();

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Editare Produs</h2>
        <form method="post" action="edit_product.php?id=<?= htmlspecialchars($product_id) ?>">
            <div class="form-group">
                <label for="name">Nume Produs:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Descriere:</label>
                <textarea name="description" id="description" required class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Preț:</label>
                <input type="number" name="price" id="price" value="<?= htmlspecialchars($product['price']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label for="stock">Stoc:</label>
                <input type="number" name="stock" id="stock" value="<?= htmlspecialchars($product['stock']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizează Produsul" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>

</html>
