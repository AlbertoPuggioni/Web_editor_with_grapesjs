<?php
// Controllo se l'utente si è autenticato
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: ../main.php");
    exit;
}