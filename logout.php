<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Golește și distruge sesiunea
$_SESSION = array();
session_destroy();

// Redirecționează către pagina de autentificare
header('Location: login.php');
exit;
