<?php
session_start(); // Avvia la sessione
global $conn;
// Connessione al database
include("../modules/connection_db.php");

// Verifica se l'utente è autenticato
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // ID dell'utente attualmente autenticato

    // Verifica se è stato inviato un ID del template per il publish
    if(isset($_GET['id'])) {
        $template_id = $_GET['id']; // ID del template selezionato dall'utente

        // Query per recuperare l'HTML e il CSS dal database per il template selezionato dall'utente
        $fetch_query = "SELECT html, css FROM templates WHERE id=? AND user_id=?";
        $fetch_stmt = $conn->prepare($fetch_query);
        $fetch_stmt->bind_param("ii", $template_id, $user_id);
        $fetch_stmt->execute();
        $template_result = $fetch_stmt->get_result();

        // Verifica se è stato trovato il template selezionato dall'utente
        if ($template_result->num_rows > 0) {
            // Ottieni il contenuto HTML e CSS dal risultato della query
            $template_row = $template_result->fetch_assoc();
            $html_content = stripslashes($template_row['html']);
            $css_content = stripslashes($template_row['css']);

            // Contenuto completo del file HTML
            $html = "<!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Template HTML</title>
                <link rel=\"stylesheet\" href=\"./style.css\">
            </head>
            <body>
                $html_content
            </body>
            </html>";

            // Percorso della cartella da creare
            $folderPath = '../../pluto';

            // Percorso dei file HTML e CSS da creare
            $indexPath = $folderPath . '/index.html';
            $cssPath = $folderPath . '/style.css';

            // Verifica se la cartella non esiste già
            if (!is_dir($folderPath)) {
                // Crea la cartella
                if (mkdir($folderPath, 0777, true)) {
                    // Scrivi il contenuto HTML nel file index.html
                    if (file_put_contents($indexPath, $html) !== false) {
                        // Scrivi il contenuto CSS nel file style.css
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

        // Chiudi il risultato della query
        $fetch_stmt->close();
    } else {
        echo "ID del template per il publish non specificato.";
    }
} else {
    // Se l'utente non è autenticato, reindirizzalo alla pagina di login
    header("Location: login.php");
    exit(); // Termina lo script per evitare che venga eseguito ulteriore codice
}

// Chiudi la connessione al database
$conn->close();
