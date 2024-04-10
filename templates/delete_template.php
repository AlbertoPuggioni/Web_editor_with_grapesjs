<?php
global $conn;

session_start();

// modulo di autenticazione dell'utente
include("../modules/authentication_user.php");

// modulo della connessione con il db
include("../modules/connection_db.php");


// Verifica se l'ID del template è stato fornito nella richiesta GET
if (isset($_GET['id'])) {

    // Escape dell'ID del template per evitare SQL injection
    $template_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Check per vedere se l'utente è sicuro di voler eliminare il template
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
        // Query per eliminare il template dal database
        $sql_delete_template = "DELETE FROM templates WHERE id=? AND user_id=?";
        $stmt = $conn->prepare($sql_delete_template);
        $stmt->bind_param("ii", $template_id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo "<script>alert('Template successfully deleted');</script>";

            // dopo aver cancellato un template, refresha la pagina di collection_templates
            header("refresh:0;url=collection_templates.php");
        } else {
            echo "Cannot delete the template: " . $conn->error;
        }
    } else {
        // Se l'utente non ha confermato, chiedi conferma
        echo "<script>   
                if (confirm('Are you sure you want delete the template?')) {
                    window.location.href = 'delete_template.php?id=$template_id&confirm=true';
                } else {
                    window.location.href = 'collection_templates.php';
                }
              </script>";
    }
}

// Chiudi la connessione con il db
$conn->close();
