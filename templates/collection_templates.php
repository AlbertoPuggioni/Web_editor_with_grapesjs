<?php
global $conn;
session_start();

// modulo per l'autenticazione dell'utente
include("../modules/authentication_user.php");

// modulo di connessione con il db
include("../modules/connection_db.php");


// Query per estrarre i template dell'utente
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, name, reg_date FROM templates WHERE user_id=? ORDER BY reg_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Chiudi la connessione con il db
$stmt->close();
$conn->close();
?>

<!--Pagina della collection templates-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Templates</title>
    <link rel="stylesheet" type="text/css" href="../templates/collection_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Per modificare l'icona nella tab del browser -->
    <link rel="icon" href="../img/collection.png" type="image/png">
    <!-- --------------------------------------------- -->
</head>
<body>
<a href="../pages/home.php"><img src="../img/home.png" id="home"></a>
<a href="../web-editor/web_editor.php"> <img src="../img/editor_collection.png" id="editor_template"></a>
<h1 class="title">Collection Templates</h1>
<div class="user">
    <img src="../img/user.png" id="user">
    <div class="user-menu">
        <ul class="hover-menu">
            <li><a href="../pages/logout.php">Logout</a></li>
        </ul>
    </div>
</div>
<table>
    <tr>
        <th class="th_template-name">Template Name</th>
        <th>Last Save</th>
        <th class="th_actions">Actions</th>
    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        // Nome del template come link che reindirizza l'utente al template selezionato
        echo "<td><a href='../web-editor/web_editor.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></td>";
        echo "<td>" . $row['reg_date'] . "</td>";
        echo "<td>";
        echo "<a href='../web-editor/web_editor.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a>          ";
        echo "<a href='../templates/duplicate_template.php?id=" . $row['id'] . "'><i class='fas fa-copy'></i></a>          ";
        echo "<a href='../templates/delete_template.php?id=" . $row['id'] . "'><i class='fas fa-trash-alt'></i></a>          ";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
