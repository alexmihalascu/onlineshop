<?php
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $order_status, $order_id);

    if ($stmt->execute()) {
        // Redirecționează înapoi la pagina de gestionare a comenzilor cu un mesaj de succes
        $_SESSION['success_message'] = "Starea comenzii a fost actualizată.";
    } else {
        // Redirecționează înapoi cu un mesaj de eroare
        $_SESSION['error_message'] = "Nu s-a putut actualiza starea comenzii.";
    }
    header('Location: manage_orders.php');
    exit;
}
