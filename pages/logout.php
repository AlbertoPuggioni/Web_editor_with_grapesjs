<?php
session_start();

// Cancella tutte le variabili di sessione
$_SESSION = array();

// Cancella il cookie di sessione
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Cancella la sessione
session_destroy();

// Reindirizza l'utente alla pagina principale
header("location: ../main.php");
exit;