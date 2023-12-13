<?php
include 'navbar.php';
include 'db_config.php';
session_start();

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

    // Adaugă produsul în baza de date
    $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$name, $description, $price])) {
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
</head>
<body>
    <div class="container">
        <h2>Adaugă un Produs Nou</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="add_product.php">
            <div class="form-group">
                <label>Nume Produs:</label>
                <input type="text" name="name" required class="form-control">
            </div>
            <div class="form-group">
                <label>Descriere:</label>
                <textarea name="description" required class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Preț:</label>
                <input type="number" name="price" required class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Adaugă Produsul" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
