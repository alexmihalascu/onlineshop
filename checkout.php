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
    $phone = $_POST['phone'];
    $street = $_POST['street'];
    $number = $_POST['number'];
    $block = $_POST['block'];
    $apartment = $_POST['apartment'];
    $city = $_POST['city'];

    try {
        // Începe tranzacția
        $conn->begin_transaction();

        // Inserează comanda în tabelul orders
        $sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Inserează detaliile comenzii în order_details
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt->execute();
        }

        // Inserează adresa de livrare
        $sql = "INSERT INTO delivery_addresses (user_id, phone, street, number, block, apartment, city) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE phone = ?, street = ?, number = ?, block = ?, apartment = ?, city = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssisssss", $user_id, $phone, $street, $number, $block, $apartment, $city, $phone, $street, $number, $block, $apartment, $city);
        $stmt->execute();

        // Confirmă tranzacția
        $conn->commit();

        // Golește coșul de cumpărături din baza de date
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();


        // Redirectare către pagina de confirmare a comenzii
        header('Location: order_confirmation.php');
        exit;
    } catch (Exception $e) {
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
</head>

<body>
    <div class="container mt-4">
        <h2>Finalizează Comanda</h2>
        <?php if ($error) : ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="checkout.php" method="post">
            <div class="mb-3">
                <label for="phone" class="form-label">Telefon:</label>
                <input type="text" name="phone" id="phone" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="street" class="form-label">Strada:</label>
                <input type="text" name="street" id="street" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="number" class="form-label">Număr:</label>
                <input type="text" name="number" id="number" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="block" class="form-label">Bloc:</label>
                <input type="text" name="block" id="block" class="form-control">
            </div>
            <div class="mb-3">
                <label for="apartment" class="form-label">Apartament:</label>
                <input type="text" name="apartment" id="apartment" class="form-control">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Oraș:</label>
                <input type="text" name="city" id="city" required class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Plasează Comanda</button>
        </form>
    </div>
</body>

</html>