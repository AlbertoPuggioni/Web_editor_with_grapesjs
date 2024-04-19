<?php
global $conn;
session_start();

// modulo per l'autenticazione dell'utente
include("../modules/authentication_user.php");

// modulo di connessione con il db
include("../modules/connection_db.php");


// Query per estrarre i template dell'utente
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, name, reg_date, imgURL FROM templates WHERE user_id=? ORDER BY reg_date DESC";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="collection_style.css">

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

<div class="container">
    <div class="row">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<div class='col-md-4'>";
            echo "<div class='card'>";
            echo "<img src='" . $row['imgURL'] . "' class='card-img-top' alt='Template Preview'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $row['name'] . "</h5>";
            echo "<p class='card-text'>Last Save: " . $row['reg_date'] . "</p>";
            echo "<a href='../web-editor/web_editor.php?id=" . $row['id'] . "' class='btn btn-primary'><i class='fas fa-edit'></i> Edit</a>";
            echo "<a href='../templates/duplicate_template.php?id=" . $row['id'] . "' class='btn btn-secondary'><i class='fas fa-copy'></i> Duplicate</a>";
            echo "<a href='../templates/delete_template.php?id=" . $row['id'] . "' class='btn btn-danger'><i class='fas fa-trash-alt'></i> Delete</a>";
            echo "<a href ='../templates/upload_template.php?id=" . $row['id'] . "' class='btn btn-info'><i class='fas fa-eye'></i> Preview</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

