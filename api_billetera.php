<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['billetera' => [], 'saldo' => 0]);
    exit;
}
include "conexion.php";
$id_usuario = $_SESSION['usuario']['id'];

// Traer saldo
$sqlSaldo = "SELECT saldo FROM usuarios WHERE id=?";
$stmtSaldo = $conn->prepare($sqlSaldo);
$stmtSaldo->bind_param("i", $id_usuario);
$stmtSaldo->execute();
$resSaldo = $stmtSaldo->get_result();
$rowSaldo = $resSaldo->fetch_assoc();
$saldo = $rowSaldo ? floatval($rowSaldo['saldo']) : 0;

// Traer billetera
$sql = "SELECT moneda, cantidad FROM billetera WHERE id_usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

$billetera = [];
while($row = $res->fetch_assoc()) {
    $billetera[$row['moneda']] = floatval($row['cantidad']);
}
echo json_encode(['billetera' => $billetera, 'saldo' => $saldo]);
?>