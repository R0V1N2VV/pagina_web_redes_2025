<?php
// saldo_usuario.php
session_start();
require_once 'conexion.php'; // Debe crear $pdo (PDO) o $conn (mysqli)

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

try {
  if (empty($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'No autenticado']);
    exit;
  }

  // === Ajustá estos nombres si tu esquema difiere ===
  $tablaUsuarios = 'usuarios';     // nombre de tu tabla
  $campoId       = 'id';           // pk del usuario
  $campoUsuario  = 'usuario';      // username
  $campoSaldo    = 'saldo_usd';    // campo de saldo (p.ej. saldo, saldo_usd)

  // Del objeto de sesión que mostras en tu HTML:
  // $_SESSION['usuario']['nombre'] existe. Intento usar id si está.
  $id   = isset($_SESSION['usuario']['id']) ? $_SESSION['usuario']['id'] : null;
  $user = isset($_SESSION['usuario']['usuario']) ? $_SESSION['usuario']['usuario'] : null;
  if (!$user && isset($_SESSION['usuario']['nombre'])) {
    // Si guardaste el mismo valor en 'nombre' como username
    $user = $_SESSION['usuario']['nombre'];
  }

  $row = null;

  // --- Con PDO ---
  if (isset($pdo)) {
    if ($id) {
      $st = $pdo->prepare("SELECT $campoSaldo AS saldo FROM $tablaUsuarios WHERE $campoId = ?");
      $st->execute([$id]);
    } else {
      $st = $pdo->prepare("SELECT $campoSaldo AS saldo FROM $tablaUsuarios WHERE $campoUsuario = ?");
      $st->execute([$user]);
    }
    $row = $st->fetch(PDO::FETCH_ASSOC);
  }
  // --- Con mysqli ---
  elseif (isset($conn)) {
    if ($id) {
      $stmt = $conn->prepare("SELECT $campoSaldo AS saldo FROM $tablaUsuarios WHERE $campoId = ?");
      $stmt->bind_param("i", $id);
    } else {
      $stmt = $conn->prepare("SELECT $campoSaldo AS saldo FROM $tablaUsuarios WHERE $campoUsuario = ?");
      $stmt->bind_param("s", $user);
    }
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
  } else {
    throw new Exception('No hay conexión a BD (pdo/conn).');
  }

  $saldo = isset($row['saldo']) ? (float)$row['saldo'] : 0.0;
  echo json_encode(['ok' => true, 'saldo' => $saldo]);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
