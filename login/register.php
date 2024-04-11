<?php
global $conn;

session_start();
include("../modules/connection_db.php");

// Dichiarazione della variabile $stmt
$stmt_check_username = null;
$stmt_check_email = null;

// Gestione dei dati del form di registrazione
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Preleva i dati inviati dal form e li pulisce
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);

    // Controlla se l'username è già presente nel database
    $sql_check_username = "SELECT * FROM users WHERE username=?";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $result_username = $stmt_check_username->get_result();

    if ($result_username->num_rows > 0) {
        $_SESSION['error_message'] = "Username already taken";
        // Alert per l'username già preso
        echo "<script>alert('Username already taken');</script>";
    }

    // Controlla se l'email è già presente nel database
    $sql_check_email = "SELECT * FROM users WHERE email=?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_email = $stmt_check_email->get_result();

    // check per vedere se la mail è già stata registrata nel db
    if ($result_email->num_rows > 0) {
        $_SESSION['error_message'] = "Email already taken";
        // Alert di errore per l'email già presa
        echo "<script>alert('Email already taken');</script>";
    }

    // Controlla la validità della password
    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
        $_SESSION['error_message'] = "Password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 8 characters long";
        // Alert per la password non valida
        echo "<script>alert('Password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 8 characters long');</script>";
    }

    // Se non ci sono errori, procede con l'inserimento nel database
    if (empty($_SESSION['error_message'])) {
        //Hash sulla password
        $hashed_password = hash('sha256', $password);

        // Query per inserire i dati nel database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // Messaggio di successo per la registrazione
            $_SESSION['success_message'] = "Registration successful!";
            // Alert per la registrazione avvenuta con successo
            echo "<script>alert('Registration successful!');</script>";

        } else {
            $_SESSION['error_message'] = "Something went wrong during the registration process: " . $stmt->error;
            // Alert per un errore durante la registrazione
            echo "<script>alert('Something went wrong during the registration process: {$stmt->error}');</script>";
        }
    }

    // Chiudi gli statement se sono stati creati
    if ($stmt_check_username) {
        $stmt_check_username->close();
    }
    if ($stmt_check_email) {
        $stmt_check_email->close();
    }
    if ($stmt) {
        $stmt->close();
    }

    // Chiudi la connessione
    $conn->close();

    // Reindirizza a main.php dopo la gestione dei dati del form
    header("location: ../main.php");
    exit;
}

// Funzione per pulire i dati del form
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

