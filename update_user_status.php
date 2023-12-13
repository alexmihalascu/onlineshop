<?php
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $user_id = $_GET['id'];
    $new_status = $_GET['status'] == '1' ? 1 : 0; // 1 pentru admin, 0 pentru user

    $sql = "UPDATE users SET is_admin = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_status, $user_id);
    $stmt->execute();

    header('Location: manage_users.php');
    exit;
}

header('Location: manage_users.php');
exit;
?>
