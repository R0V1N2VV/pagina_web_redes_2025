<?php
session_start();
include("conexion.php");
if (!isset($_SESSION['usuario'])) { echo "login.php"; exit(); }
if (!isset($_POST['moneda'], $_POST['cantidad'], $_POST['precio'])) { echo "Faltan datos"; exit(); }

$id_usuario = $_SESSION['usuario']['id'];
$moneda = trim($_POST['moneda']);
$cantidad = floatval($_POST['cantidad']);
$precio = floatval($_POST['precio']);
if ($moneda === '' || $cantidad <= 0 || $precio <= 0) { echo "Datos inválidos"; exit(); }

// Validar saldo
$sqlSaldo = "SELECT saldo FROM usuarios WHERE id = ?";
$stmtSaldo = $conn->prepare($sqlSaldo);
$stmtSaldo->bind_param("i", $id_usuario);
$stmtSaldo->execute();
$resSaldo = $stmtSaldo->get_result();
if ($rowSaldo = $resSaldo->fetch_assoc()) {
    $saldo = floatval($rowSaldo['saldo']);
    $totalCompra = $cantidad * $precio;
    if ($saldo < $totalCompra) { echo "Saldo insuficiente"; exit(); }
    // Descontar saldo
    $nuevoSaldo = $saldo - $totalCompra;
    $sqlUpdate = "UPDATE usuarios SET saldo = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("di", $nuevoSaldo, $id_usuario);
    $stmtUpdate->execute();

    // Actualizar billetera (sumar cantidad)
    $sqlB = "INSERT INTO billetera (id_usuario, moneda, cantidad) VALUES (?, ?, ?) 
             ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
    $stmtB = $conn->prepare($sqlB);
    $stmtB->bind_param("isd", $id_usuario, $moneda, $cantidad);
    $stmtB->execute();

    // Registrar en historial
    $sqlH = "INSERT INTO historial (id_usuario, moneda, cantidad, precio, tipo) VALUES (?, ?, ?, ?, 'compra')";
    $stmtH = $conn->prepare($sqlH);
    $stmtH->bind_param("isdd", $id_usuario, $moneda, $cantidad, $precio);
    $stmtH->execute();

    echo "OK";
} else {
    echo "Usuario no encontrado";
}
?>