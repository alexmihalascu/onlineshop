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

$sql = "SELECT o.order_id, u.username, o.order_date, o.order_status, da.address, 
        SUM(p.price * od.quantity) AS total
        FROM orders o
        JOIN order_details od ON o.order_id = od.order_id
        JOIN products p ON od.product_id = p.product_id
        JOIN users u ON o.user_id = u.user_id
        LEFT JOIN delivery_addresses da ON o.user_id = da.user_id
        GROUP BY o.order_id";
$result = $conn->query($sql);
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
    <title>Gestionare Comenzi</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container my-4">
        <h2>Gestionarea Comenzilor</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Comandă</th>
                        <th>Utilizator</th>
                        <th>Data Comenzii</th>
                        <th>Detalii Comandă</th>
                        <th>Adresa de Livrare</th>
                        <th>Suma Totală</th>
                        <th>Stare</th>
                        <th>Actualizare Stare</th>
                        <th>Ștergere</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['username']) ?></td>
                            <td><?= $order['formatted_order_date'] ?></td>
                            <td>
                                <?php foreach ($orderDetails[$order['order_id']] as $detail): ?>
                                    <p><?= htmlspecialchars($detail['name']) ?> - <?= $detail['quantity'] ?> buc. (<?= number_format($detail['price'], 2) ?> Lei/buc)</p>
                                <?php endforeach; ?>
                            </td>
                            <td><?= htmlspecialchars($order['address']) ?></td>
                            <td><?= number_format($order['total'], 2) ?> Lei</td>
                            <td><?= htmlspecialchars($order['order_status']) ?></td>
                            <td>
                                <form action="update_order_status.php" method="post" class="d-flex justify-content-center">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <select name="order_status" class="form-select form-select-sm mx-2">
                                        <option value="Efectuată" <?= $order['order_status'] == 'Efectuată' ? 'selected' : '' ?>>Efectuata</option>
                                        <option value="Neefectuată" <?= $order['order_status'] == 'Neefectuată' ? 'selected' : '' ?>>Neefectuata</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Actualizează</button>
                                </form>
                            </td>
                            <td>
                                <form action="delete_order.php" method="post" class="d-grid">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm btn-block">Șterge</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
