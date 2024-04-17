<?php
// Avvia la sessione
session_start();
global $conn;

// Connessione al database
include("../modules/connection_db.php");

// Verifica se l'utente è autenticato per mezzo dell'id
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Verifica se è stato inviato un ID del template per il publish
    if(isset($_GET['id'])) {
        $template_id = $_GET['id'];

        // Recupera l'HTML e il CSS dal db per il template selezionato dall'utente
        $fetch_query = "SELECT name, html, css FROM templates WHERE id=? AND user_id=?";
        $fetch_stmt = $conn->prepare($fetch_query);
        $fetch_stmt->bind_param("ii", $template_id, $user_id);
        $fetch_stmt->execute();
        $template_result = $fetch_stmt->get_result();

        // Se è stato trovato un template, allora...
        if ($template_result->num_rows > 0) {
            // ... ottiene il contenuto HTML e CSS dal risultato della query
            $template_row = $template_result->fetch_assoc();
            $html_content = stripslashes($template_row['html']);
            $css_content = stripslashes($template_row['css']);
            $name_content = stripslashes($template_row['name']);

            // Contenuto completo del file HTML
            $html = "<!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>$name_content</title>
                <link rel=\"stylesheet\" href=\"./style.css\">
            </head>
            <body>
                $html_content
            </body>
            </html>";

            // Percorso della cartella da creare --> TODO: creare una cartella generale per ogni singolo utente al cui interno ci sono n dir diverse in base al numero dei template fatti
            $folderPath = '../../Published/utente_1';

            // Percorso dei file HTML e CSS da creare
            $indexPath = $folderPath . '/index.html';
            $cssPath = $folderPath . '/style.css';

            // Verifica se la cartella non esiste già
            // TODO --> implementare la funzionalità di sovrascrivere eventualmente una dir già creata in base al contenuto
            if (!is_dir($folderPath)) {
                // Crea la cartella
                if (mkdir($folderPath, 0777, true)) {
                    // contenuto HTML nel file index.html
                    if (file_put_contents($indexPath, $html) !== false) {
                        // Scrive il contenuto CSS nel file style.css
                        if (file_put_contents($cssPath, $css_content) !== false) {
                            echo "I file HTML e CSS sono stati creati con successo.";
                        } else {
                            echo "Si è verificato un errore durante la creazione del file CSS.";
                        }
                    } else {
                        echo "Si è verificato un errore durante la creazione del file HTML.";
                    }
                } else {
                    echo "Si è verificato un errore durante la creazione della cartella \"$folderPath\".";
                }
            } else {
                echo "La cartella \"$folderPath\" esiste già.";
            }
        } else {
            echo "Nessun template trovato per l'utente attualmente autenticato con l'ID specificato.";
        }

        // Chiude il risultato della query
        $fetch_stmt->close();
    } else {
        echo "ID del template per il publish non specificato.";
    }
} else {
    // Se l'utente non è autenticato, viene reindirizzato alla pagina di login
    header("Location: main.php");
    exit();
}

// Chiudi la connessione al database
$conn->close();

// TODO --> cambiare il path dove viene creata la directory.
// Passaggi da eseguire:
// 1) creare una cartella Published nella directory htdocs
// 2) spostare la cartella generata da questo script in Published
// 3) Visitare il server locale dove viene pubblicato il template
// il template viene visualizzato a questo indirizzo: http://localhost/Published/pluto/index.html