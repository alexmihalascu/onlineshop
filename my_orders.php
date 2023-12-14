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

$sql = "SELECT o.order_id, o.order_date, o.order_status, da.address, 
        SUM(p.price * od.quantity) AS total
        FROM orders o
        JOIN order_details od ON o.order_id = od.order_id
        JOIN products p ON od.product_id = p.product_id
        LEFT JOIN delivery_addresses da ON o.user_id = da.user_id
        WHERE o.user_id = ?
        GROUP BY o.order_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

$orderDetails = [];
foreach ($orders as $order) {
    $sql = "SELECT p.name, od.quantity, p.price FROM order_details od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order['order_id']);
    $stmt->execute();
    $details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $orderDetails[$order['order_id']] = $details;
}

foreach ($orders as $key => $order) {
    $formattedDate = date('d.m.Y H:i:s', strtotime($order['order_date']));
    $orders[$key]['formatted_order_date'] = $formattedDate;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comenzile Mele</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<body>
    <div class="container mt-4">
        <h2>Comenzile Mele</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Comandă</th>
                        <th>Data Comenzii</th>
                        <th>Detalii Comandă</th>
                        <th>Adresa de Livrare</th>
                        <th>Suma Totală</th>
                        <th>Stare</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= $order['formatted_order_date'] ?></td>
                            <td>
                                <?php foreach ($orderDetails[$order['order_id']] as $detail): ?>
                                    <p><?= htmlspecialchars($detail['name']) ?> - <?= $detail['quantity'] ?> buc. (<?= number_format($detail['price'], 2) ?> Lei/buc)</p>
                                <?php endforeach; ?>
                            </td>
                            <td><?= htmlspecialchars($order['address']) ?></td>
                            <td><?= number_format($order['total'], 2) ?> Lei</td>
                            <td><?= htmlspecialchars($order['order_status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
