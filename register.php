<?php
include 'navbar.php';
include 'db_config.php';

$error = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "A apărut o eroare.";
        }
    } else {
        $error = "Numele de utilizator sau emailul este deja înregistrat.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Înregistrare</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p>Contul a fost creat cu succes. <a href="login.php">Autentifică-te aici</a>.</p>
        <?php else: ?>
            <form method="post" action="register.php">
                <div class="form-group">
                    <label>Nume de utilizator:</label>
                    <input type="text" name="username" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Parolă:</label>
                    <input type="password" name="password" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" value="Înregistrează-te" class="btn btn-primary">
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
