<?php
session_start();
include 'navbar.php';
include 'db_config.php';

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
</head>
<body>
    <div class="container">
        <h1>Bine ați venit la Magazinul Meu Online</h1>
        <!-- Aici poți adăuga conținutul paginii, de exemplu, produsele de top -->
    </div>
</body>
</html>
