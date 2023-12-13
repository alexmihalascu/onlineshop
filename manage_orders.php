<?php
include 'navbar.php';
include 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$sql = "SELECT order_id, user_id, order_date FROM orders";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Comenzi</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Gestionarea Comenzilor</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Comandă</th>
                    <th>ID Utilizator</th>
                    <th>Data Comenzii</th>
                    <!-- Alte coloane după necesități -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['user_id']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <!-- Alte coloane și acțiuni -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
