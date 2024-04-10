<?php
session_start();

// modulo di autenticazione dell'utente
include("../modules/authentication_user.php");

// modulo della connessione con il db
include("../modules/connection_db.php");


// Verifica se l'ID del template è stato fornito nella richiesta GET
if (isset($_GET['id'])) {

    // Escape dell'ID del template per evitare SQL injection
    $template_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query per eliminare il template dal database
    $sql_delete_template = "DELETE FROM templates WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql_delete_template);
    $stmt->bind_param("ii", $template_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "<script>alert('Template successfully deleted ');</script>";

    // dopo aver cancellato un template, refresha la pagina di collection_templates
        header("refresh:0;url=collection_templates.php");
    } else {
        echo "Cannot deleted the template: " . $conn->error;
    }
}

// Chiudi la connessione con il db
$conn->close();