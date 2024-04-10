<?php
global $conn;
// Connessione con il db
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "cms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione con il db
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}