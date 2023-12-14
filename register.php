<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Verifică dacă există deja un utilizator cu acest username sau email
    $sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Inserează noul utilizator în baza de date
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <?php if ($success): ?>
        <!-- Redirecționare automată după 2 secunde -->
        <meta http-equiv="refresh" content="2;url=login.php">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Înregistrare</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">Contul a fost creat cu succes. Vei fi redirecționat la pagina de autentificare.</div>
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