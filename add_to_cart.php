<?php
include 'db_config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['product_id'])) {
    header("Location: login.php"); // Redirecționează utilizatorii neautentificați
    exit;
}

$product_id = $_GET['product_id'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // Presupunem că quantity este trimis ca parametru GET
$user_id = $_SESSION['user_id'];

$sql = "SELECT cart_id FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
} else {
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
}
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $product_id, $quantity);
$stmt->execute();

// Setați o variabilă de sesiune pentru a indica adăugarea cu succes în coș
$_SESSION['cart_success'] = true;

header("Location: product_list.php"); // Redirecționează înapoi la lista de produse
exit;
?>
