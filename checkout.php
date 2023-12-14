<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        // Începe tranzacția
        $conn->begin_transaction();

        // Inserează comanda în tabelul orders
        $sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Eroare la inserarea comenzii.");
        }
        $order_id = $conn->insert_id;

        // Inserează detaliile comenzii în order_details
        $sql = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt->bind_param("iii", $order_id, $product_id, $quantity);
            if (!$stmt->execute()) {
                throw new Exception("Eroare la inserarea detaliilor comenzii.");
            }
        }

        // Inserează adresa de livrare
        $sql = "INSERT INTO delivery_addresses (user_id, address) VALUES (?, ?) ON DUPLICATE KEY UPDATE address = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $address, $address);
        if (!$stmt->execute()) {
            throw new Exception("Eroare la inserarea adresei de livrare.");
        }

        // Confirmă tranzacția
        $conn->commit();

        // Golește coșul de cumpărături
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Golirea coșului din sesiune
        $_SESSION['cart'] = array();

        // Redirectare către pagina de confirmare a comenzii
        header('Location: order_confirmation.php');
        exit;
    } catch (Exception $e) {
        // Revenire în caz de eroare
        $conn->rollback();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<body>
    <div class="container mt-4">
        <h2>Finalizează Comanda</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="checkout.php" method="post">
            <div class="mb-3">
                <label for="address" class="form-label">Adresa de Livrare:</label>
                <textarea name="address" id="address" required class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Plasează Comanda</button>
        </form>
    </div>
</body>
</html>

