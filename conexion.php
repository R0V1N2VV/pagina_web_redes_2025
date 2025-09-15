<?php
$servername = "localhost";
$username = "root";
$password = ""; // deja vacío si tu XAMPP no tiene contraseña
$dbname = "criptosim"; // nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
