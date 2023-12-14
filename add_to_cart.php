<?php
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_GET['product_id'])) {
    header("Location: login.php"); // Redirecționează utilizatorii neautentificați
    exit;
}

$product_id = $_GET['product_id'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
$user_id = $_SESSION['user_id'];

// Reducerea stocului
$sql = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $quantity, $product_id);
$stmt->execute();


$sql = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Produsul există în coș, actualizează cantitatea
    $newQuantity = $row['quantity'] + $quantity;
    $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $newQuantity, $user_id, $product_id);
} else {
    // Produsul nu există în coș, inserează un nou articol
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
}

$stmt->execute();
$_SESSION['cart_success'] = true;
header("Location: product_list.php"); // Redirecționează înapoi la lista de produse
exit;
