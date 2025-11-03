<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "cripto_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
