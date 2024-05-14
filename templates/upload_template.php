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
        $fetch_query = "SELECT u.username, t.name, t.html, t.css FROM users as u INNER JOIN templates t ON u.id = t.user_id WHERE t.id=? AND t.user_id=?";
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
            $username = stripslashes($template_row['username']);

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

            // Percorso della cartella che viene creata (la dir viene creata con lo stesso nome del template di collection template)
            $folderPath = '../../Published/' . $username . '/' . $name_content;


            // Percorso dei file HTML e CSS da creare
            $indexPath = $folderPath . '/index.html';
            $cssPath = $folderPath . '/style.css';

            // Verifica se la cartella non esiste già
            if (!is_dir($folderPath)) {
                // Se la cartella non esiste, la crea e crea i file al suo interno
                if (mkdir($folderPath, 0777, true)) {
                    // Contenuto HTML nel file index.html
                    if (file_put_contents($indexPath, $html) !== false) {
                        // Scrive il contenuto CSS nel file style.css
                        if (file_put_contents($cssPath, $css_content) !== false) {
                            echo "<script>alert('HTML and CSS files are successfully created.')</script>";
                            echo "<script>window.location.href = 'http://localhost/Published/$username/$name_content/index.html';</script>";
                        } else {
                            echo "Ops. Something went wrong while creating CSS file.";
                        }
                    } else {
                        echo "Ops. Something went wrong while creating HTML file.";
                    }
                } else {
                    echo "Ops. Something went wrong while creating the user's directory.\"$folderPath\".";
                }
            } else {
                // La cartella esiste già, quindi sovrascrive i file HTML e CSS al suo interno
                // Contenuto HTML nel file index.html
                if (file_put_contents($indexPath, $html) !== false) {
                    // Scrive il contenuto CSS nel file style.css
                    if (file_put_contents($cssPath, $css_content) !== false) {
                        echo "<script>alert('HTML & CSS files successfully updated.')</script>";
                        echo "<script>window.location.href = 'http://localhost/Published/$username/$name_content/index.html';</script>";
                    } else {
                        echo "Something went wrong during the CSS file's update.";
                    }
                } else {
                    echo "Something went wrong during the HTML file's update.";
                }
            }
        } else {
            echo "Seems like the template you are looking for does not exists.";
        }
        // Chiude il risultato della query
        $fetch_stmt->close();
    }
} else {
    // Se l'utente non è autenticato, viene reindirizzato alla pagina di login
    header("Location: main.php");
    exit();
}

$conn->close();
