<?php
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Începe tranzacția
    $conn->begin_transaction();

    try {
        // Șterge înregistrările asociate din tabela order_details
        $sql = "DELETE od FROM order_details od JOIN orders o ON od.order_id = o.order_id WHERE o.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Șterge înregistrările asociate din tabela orders
        $sql = "DELETE FROM orders WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Șterge înregistrările asociate din tabela delivery_addresses
        $sql = "DELETE FROM delivery_addresses WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Acum, șterge utilizatorul
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Confirmă tranzacția
        $conn->commit();

        // Redirecționează înapoi la pagina de gestionare a utilizatorilor
        header('Location: manage_users.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        // Revenire în caz de eroare
        $conn->rollback();
        // Tratează eroarea, de exemplu, prin afișarea unui mesaj
        echo "A apărut o eroare: " . $e->getMessage();
        exit;
    }
}

// Redirecționează înapoi dacă ID-ul nu este setat
header('Location: manage_users.php');
exit;
