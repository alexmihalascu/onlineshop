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

$sql = "SELECT COUNT(*) AS user_count FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$user_count = $result->fetch_assoc()['user_count'];

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
        <h1 class="text-center">Bine ați venit la Magazin Online !</h1>
        <p class="text-center">Magazinul nostru online vă oferă o gamă largă de produse de calitate la prețuri accesibile.</p>
        <br>

        <!-- Secțiunea Număr Utilizatori -->
        <div class="user-count-section text-center">
            <h2><?= $user_count ?> clienți au încredere în noi!</h2>
            <p>Mulțumim fiecărui client pentru alegerea noastră.</p>
        </div>

        <!-- Alte secțiuni informative sau descriptive -->
        <div class="about-section text-center">
            <br>
            <h2>Despre Magazinul Nostru</h2>
            <br>
            <div class="history ">
                <h3>Istoria Noastră</h3>
                <p>Încă de la fondarea noastră în anul 2022, magazinul nostru a avut ca misiune să ofere produse de cea mai bună calitate la prețuri accesibile. De-a lungul anilor, am crescut alături de comunitatea noastră și am extins gama de produse pentru a satisface nevoile în continuă schimbare ale clienților noștri.</p>
            </div>
            <div class="mission">
                <h3>Misiunea Noastră</h3>
                <p>Misiunea noastră este să oferim experiențe de cumpărături excepționale, susținând totodată practici durabile de afaceri și contribuind la bunăstarea comunității noastre.</p>
            </div>
            <div class="vision">
                <h3>Viziunea Noastră</h3>
                <p>Ne imaginăm un viitor în care fiecare cumpărătură aduce nu doar valoare clienților noștri, ci și un impact pozitiv asupra societății și mediului înconjurător.</p>
            </div>
        </div>
    </div>
</body>

</html>