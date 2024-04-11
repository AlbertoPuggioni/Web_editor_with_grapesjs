<?php
global $conn;
session_start();

// Controllo se l'utente è già autenticato
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Reindirizza alla pagina home.php se l'utente è già autenticato
    header("location: ../pages/home.php");
    exit;
}

// Modulo della connessione al database
include("../modules/connection_db.php");

// Gestione dei dati del form di login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Prende i dati dal form di login
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    // Hashing della password
    $login_password = hash('sha256', $login_password);

    // Query per verificare le credenziali presenti nel db
    $sql = "SELECT id, password FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Controlla se la query restituisce un record
    if ($result->num_rows == 1) {
        // Utente trovato, verifica la password
        $row = $result->fetch_assoc();

        // Controlla se la password non coincide
        if ($login_password !== $row['password']) {
            // Imposta il messaggio di errore nella variabile di sessione
            $_SESSION['error_message'] = "Incorrect email or password";
        } else {
            // La password coincide, definisce la sessione
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['id'];
            // Reindirizzamento alla home
            header("location: ../pages/home.php");
            exit;
        }
    } else {
        // L'utente non è trovato, imposta il messaggio di errore
        $_SESSION['error_message'] = "Incorrect email or password";
    }

    // Chiude la connessione e il prepared statement
    $stmt->close();
    $conn->close();
}

// Reindirizza a main.php dopo il controllo
header("location: ../main.php");
exit;

