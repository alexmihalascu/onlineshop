<?php
session_start();
include 'navbar.php';
include 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    // Dacă nu este autentificat, redirecționează-l la pagina de autentificare
    header("Location: login.php");
    exit; // Asigură-te că nu se continuă procesarea scriptului după redirecționare
}

// Aici poți adăuga logica pentru a prelua produsele de top sau promoțiile
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazinul Meu Online</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Bine ați venit la Magazinul Nostru Online</h1>
        <p>Magazinul nostru online vă oferă o gamă largă de produse de calitate la prețuri accesibile.</p>
    </div>
</body>
</html>
