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

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Începe tranzacția
    $conn->begin_transaction();

    try {
        // Șterge înregistrările din tabelul cart legate de produsul respectiv
        $sqlDeleteCart = "DELETE FROM cart WHERE product_id = ?";
        $stmtDeleteCart = $conn->prepare($sqlDeleteCart);
        $stmtDeleteCart->bind_param("i", $product_id);
        $stmtDeleteCart->execute();

        // Șterge înregistrările din tabelul order_details legate de produsul respectiv
        $sqlDeleteOrderDetails = "DELETE FROM order_details WHERE product_id = ?";
        $stmtDeleteOrderDetails = $conn->prepare($sqlDeleteOrderDetails);
        $stmtDeleteOrderDetails->bind_param("i", $product_id);
        $stmtDeleteOrderDetails->execute();

        // Apoi, șterge produsul din tabelul products
        $sqlDeleteProduct = "DELETE FROM products WHERE product_id = ?";
        $stmtDeleteProduct = $conn->prepare($sqlDeleteProduct);
        $stmtDeleteProduct->bind_param("i", $product_id);
        $stmtDeleteProduct->execute();

        // Confirmă tranzacția
        $conn->commit();

        // Redirecționează înapoi la lista de produse
        header('Location: product_list.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        // Revenire în caz de eroare
        $conn->rollback();
        echo "<p>A apărut o eroare: " . $e->getMessage() . "</p>";
        exit;
    }
}
