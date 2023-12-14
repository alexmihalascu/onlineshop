<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Preluarea ultimei comenzi plasate de utilizator
$sql = "SELECT order_id, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Preluarea adresei de livrare pentru comanda curentă
$sql = "SELECT phone, street, number, block, apartment, city FROM delivery_addresses WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$address_result = $stmt->get_result();
$address = $address_result->fetch_assoc();

// Preluarea detaliilor ultimei comenzi
$sql = "SELECT p.name, p.price, od.quantity FROM order_details od INNER JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order['order_id']);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmare Comandă</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2>Confirmare Comandă</h2>
        <p class="mt-3">Comanda ta cu ID-ul <strong><?= $order['order_id'] ?></strong> a fost plasată cu succes!</p>

        <div class="order-details mt-4">
            <h3>Detalii Comandă</h3>
            <p>Data Comenzii: <?= $order['order_date'] ?></p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Produs</th>
                            <th>Preț</th>
                            <th>Cantitate</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($items as $item) :
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= number_format($item['price'], 2) ?> Lei</td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($subtotal, 2) ?> Lei</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">Total General</td>
                            <td><?= number_format($total, 2) ?> Lei</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="delivery-details mt-4">
            <h3>Detalii Livrare</h3>
            <p>Telefon: <?= htmlspecialchars($address['phone']) ?></p>
            <p>Adresă: <?= htmlspecialchars($address['street']) ?>, Nr. <?= htmlspecialchars($address['number']) ?><?= $address['block'] ? ', Bl. ' . htmlspecialchars($address['block']) : '' ?><?= $address['apartment'] ? ', Ap. ' . htmlspecialchars($address['apartment']) : '' ?>, <?= htmlspecialchars($address['city']) ?></p>
        </div>

        <p class="mt-4">Îți mulțumim pentru comandă, <?= htmlspecialchars($_SESSION['username']) ?>! Comanda ta a fost procesată și este în curs de pregătire.</p>
    </div>
</body>

</html>