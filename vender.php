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

// Verificar que el usuario tiene suficiente cantidad para vender
$sql = "SELECT cantidad FROM billetera WHERE id_usuario = ? AND moneda = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id_usuario, $moneda);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$total = $row ? floatval($row['cantidad']) : 0;

if ($total < $cantidad) { echo "No tienes suficientes activos para vender"; exit(); }

// Actualizar billetera (restar cantidad)
$sqlB = "UPDATE billetera SET cantidad = cantidad - ? WHERE id_usuario = ? AND moneda = ?";
$stmtB = $conn->prepare($sqlB);
$stmtB->bind_param("dis", $cantidad, $id_usuario, $moneda);
$stmtB->execute();

// Sumar saldo al usuario
$sqlSaldo = "SELECT saldo FROM usuarios WHERE id = ?";
$stmtSaldo = $conn->prepare($sqlSaldo);
$stmtSaldo->bind_param("i", $id_usuario);
$stmtSaldo->execute();
$resSaldo = $stmtSaldo->get_result();
if ($rowSaldo = $resSaldo->fetch_assoc()) {
    $saldo = floatval($rowSaldo['saldo']);
    $nuevoSaldo = $saldo + ($cantidad * $precio);
    $sqlUpdate = "UPDATE usuarios SET saldo = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("di", $nuevoSaldo, $id_usuario);
    $stmtUpdate->execute();
}

// Registrar en historial
$sqlH = "INSERT INTO historial (id_usuario, moneda, cantidad, precio, tipo) VALUES (?, ?, ?, ?, 'venta')";
$stmtH = $conn->prepare($sqlH);
$stmtH->bind_param("isdd", $id_usuario, $moneda, $cantidad, $precio);
$stmtH->execute();

echo "OK";
?>