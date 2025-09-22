<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id_usuario = $_SESSION['usuario']['id']; // asegurate de que en el login guardes 'id'
$moneda = $_POST['moneda'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];

// Evitar valores vacíos
if ($cantidad <= 0) {
    header("Location: index.php?error=cantidad_invalida");
    exit();
}

// Insertar en la tabla compras
$sql = "INSERT INTO compras (id_usuario, moneda, cantidad, precio) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isdd", $id_usuario, $moneda, $cantidad, $precio);

if ($stmt->execute()) {
    header("Location: mi_cuenta.php?msg=compra_ok");
    exit();
} else {
    echo "Error en la compra: " . $stmt->error;
}
?>
