<?php
session_start();
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];

    // Actualizați numele de utilizator și parola
    $sql = "UPDATE users SET username = ?, password = ? WHERE user_id = ?";
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $hashedNewPassword, $user_id); // Legați parametrii la instrucțiunea pregătită
    if ($stmt->execute()) {
        $success = "Profilul a fost actualizat.";
    } else {
        $error = "A apărut o eroare la actualizarea profilului.";
    }
}

// Selectați numele de utilizator pentru a afișa în formular
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Legați parametrul user_id la instrucțiunea pregătită
$stmt->execute();
$stmt->bind_result($currentUsername); // Legați rezultatul la variabila $currentUsername
$stmt->fetch(); // Obțineți valoarea

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilul Meu</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h2>Profilul Meu</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <form method="post" action="user_profile.php">
            <div class="form-group">
                <label>Nume de utilizator:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($currentUsername) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Noua Parola:</label>
                <input type="password" name="new_password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizează Profilul" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
