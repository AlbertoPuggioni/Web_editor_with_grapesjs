<?php
session_start();

// Connessione al db
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'cms';

// Check della connessione
$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) {
    die('Connection failed : ' . $conn->connect_error);
}

// Creazione del db
$sql_create_database = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql_create_database) === TRUE) {
    echo "Database '$database' successfully created.\r\n";
} else {
    echo "Error during database's creation '$database': " . $conn->error;
}

// Selezione del database
$conn->select_db($database);


// Creazione della tabella 'users'
$sql_create_users_table = "CREATE TABLE IF NOT EXISTS users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
            )";


// check per vedere se la tabella users è stata creata con successo oppure no
if ($conn->query($sql_create_users_table) === TRUE) {
    echo "Table 'users' successfully created.\r\n";
} else {
    echo "Error during the creation of the table 'users': " . $conn->error;
}

// Creazione della tabella 'templates'
$sql_create_templates_table = "CREATE TABLE IF NOT EXISTS templates (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            html LONGTEXT NOT NULL,
            css LONGTEXT NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id BIGINT UNSIGNED,
            imgURL LONGBLOB NOT NULL, 
            FOREIGN KEY (user_id) REFERENCES users(id)
            )";
// si può modificare il LONGBLOB con il LONGTEXT --> in questo momento è settato a LONGBLOB per la collection_templates

// check per vedere se la tabella templates è stata creata con successo
if ($conn->query($sql_create_templates_table) === TRUE) {
    echo "Table 'templates' successfully created.\r\n";
} else {
    echo "Error during the creation of the table 'templates': " . $conn->error;
}

// chiusura della connessione
$conn->close();
