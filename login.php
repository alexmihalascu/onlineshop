<?php
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Autentificare</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Nume de utilizator:</label>
                <input type="text" name="username" id="username" required class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Parolă:</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Autentificare" class="btn btn-primary">
            </div>
            <?php if ($error) : ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>