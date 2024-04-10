<?php
global $conn;
include("connection_db.php");

// Se l'ID del template non è fornito, crea un nuovo template e ottieni l'ID
if (!isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_check_template = "SELECT id FROM templates WHERE html='' AND css='' AND user_id='$user_id'";
    $result_check_template = $conn->query($sql_check_template);

    if ($result_check_template && $result_check_template->num_rows > 0) {
        $row = $result_check_template->fetch_assoc();
        $template_id = $row['id'];
    } else {
        $default_name = "Template";
        $sql = "INSERT INTO templates (html, css, name, user_id, reg_date) VALUES ('', '', ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $default_name, $_SESSION['user_id']);
        $stmt->execute();
        $template_id = $stmt->insert_id;
        $stmt->close();
    }
} else {
    $template_id = $_GET['id'];
}

// Preleva il template corrispondente dall'ID fornito
$sql = "SELECT html, css, name FROM templates WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$result = $stmt->get_result();


$template_data = $result->fetch_assoc();

// Se i dati del form sono stati inviati, esegui l'aggiornamento del template
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST) && isset($_POST['html']) && isset($_POST['css']) && isset($_POST['name']) && !empty($_POST['html']) && !empty($_POST['css'])) {
    // Assicurati che il nome del template sia stato fornito
    if (empty($_POST['name'])) {
        // Mostra un alert e interrompi l'esecuzione dello script
        echo "Please enter a name for the template";
        exit;
    }

    // Assicurati che i dati siano stati inviati correttamente
    $html = $_POST['html'];
    $css = $_POST['css'];
    $templateName = $_POST['name'];

    // Escape dei dati prima dell'inserimento nel database per evitare SQL injection
    $html = mysqli_real_escape_string($conn, $html);
    $css = mysqli_real_escape_string($conn, $css);
    $templateName = mysqli_real_escape_string($conn, $templateName);

    // Esegui un'operazione di aggiornamento del template nel database
    $sql_update_data = "UPDATE templates SET html=?, css=?, name=?, reg_date=NOW() WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql_update_data);
    $stmt->bind_param("sssii", $html, $css, $templateName, $template_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo "Template successfully saved. \r\n";
    } else {
        echo 'Errore: ' . $conn->error;
    }
    exit;
}