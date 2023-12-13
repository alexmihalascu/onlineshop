<?php
include 'navbar.php';
include 'db_config.php';
session_start();

// Verifică dacă utilizatorul este admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Șterge produsul din baza de date
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$product_id])) {
        header('Location: product_list.php');
    } else {
        echo "<p>A apărut o eroare la ștergerea produsului.</p>";
    }
}
?>
