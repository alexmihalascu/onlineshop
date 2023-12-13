<?php
include 'db_config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    // Oferă un feedback sau redirecționează utilizatorul
    header('Location: cart.php'); // Redirecționează înapoi la pagina coșului
    exit;
} else {
    echo "Eroare: Nu a fost specificat niciun ID de produs.";
}
?>
