<?php
include 'navbar.php';
include 'db_config.php';
session_start();

// Verifică dacă utilizatorul este autentificat și dacă coșul este gol
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';

// Procesarea comenzii la trimiterea formularului
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address']; // Adresa de livrare

    try {
        $conn->beginTransaction();

        // Inserează comanda în tabelul orders
        $sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt->execute([$user_id])) {
            throw new Exception("Eroare la inserarea comenzii.");
        }
        $order_id = $conn->lastInsertId();

        // Inserează detaliile comenzii în order_details
        $sql = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            if (!$stmt->execute([$order_id, $product_id, $quantity])) {
                throw new Exception("Eroare la inserarea detaliilor comenzii.");
            }
        }

        // Inserează adresa de livrare
        $sql = "INSERT INTO delivery_addresses (user_id, address) VALUES (?, ?) ON DUPLICATE KEY UPDATE address = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt->execute([$user_id, $address, $address])) {
            throw new Exception("Eroare la inserarea adresei de livrare.");
        }

        $conn->commit();
        $_SESSION['cart'] = array();
        header('Location: order_confirmation.php');
        exit;
    } catch (Exception $e) {
        $conn->rollBack();
        $error = "A apărut o eroare la procesarea comenzii: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizare Comandă</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Finalizează Comanda</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="checkout.php" method="post">
            <div class="form-group">
                <label>Adresa de Livrare:</label>
                <textarea name="address" required class="form-control"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Plasează Comanda" class="btn btn-success">
            </div>
        </form>
    </div>
</body>
</html>
