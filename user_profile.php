<?php
include 'navbar.php';
include 'db_config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$username, $email, $user_id])) {
        $success = "Profilul a fost actualizat.";
    } else {
        $error = "A apărut o eroare la actualizarea profilului.";
    }
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilul Meu</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Profilul Meu</h2>
        <form method="post" action="user_profile.php">
            <div class="form-group">
                <label>Nume de utilizator:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizează Profilul" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
