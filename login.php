<?php
include 'navbar.php';
include 'db_config.php';

session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, password, is_admin FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $row['is_admin'];
            header("location: index.php");
        } else {
            $error = "Numele de utilizator sau parola incorectă.";
        }
    } else {
        $error = "Numele de utilizator sau parola incorectă.";
    }
    $stmt->close();
}
?>  

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Autentificare</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <label>Nume de utilizator:</label>
                <input type="text" name="username" required class="form-control">
            </div>
            <div class="form-group">
                <label>Parolă:</label>
                <input type="password" name="password" required class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Autentificare" class="btn btn-primary">
            </div>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
