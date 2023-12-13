<?php
include 'navbar.php';
include 'db_config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Actualizarea cantităților din coș
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if ($quantity == 0) {
            // Șterge produsul din coș
            $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
        } else {
            // Actualizează cantitatea
            $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $quantity, $user_id, $product_id);
            $stmt->execute();
        }
    }
}

// Preluarea produselor din coș
$sql = "SELECT p.product_id, p.name, p.price, c.quantity FROM products p INNER JOIN cart c ON p.product_id = c.product_id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coș de Cumpărături</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <h2>Coșul tău de cumpărături</h2>
        <form action="cart.php" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produs</th>
                        <th>Cantitate</th>
                        <th>Preț</th>
                        <th>Total</th>
                        <th>Acțiune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($cart_items as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $item['product_id'] ?>]" value="<?= $item['quantity'] ?>" min="0">
                            </td>
                            <td><?= number_format($item['price'], 2) ?> Lei</td>
                            <td><?= number_format($subtotal, 2) ?> Lei</td>
                            <td>
                                <a href="delete_from_cart.php?product_id=<?= $item['product_id'] ?>" class="btn btn-danger">Șterge</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>Total: <?= number_format($total, 2) ?> Lei</p>
            <input type="submit" name="update" value="Actualizează Coșul" class="btn btn-primary">
            <a href="checkout.php" class="btn btn-success">Finalizează Comanda</a>
        </form>
    </div>
</body>
</html>
