<?php
header("Content-Type: application/json");
require_once 'conexion.php';


if (isset($_GET['simbolo'])) {
  $simbolo = strtoupper($_GET['simbolo']);
  $stmt = $conn->prepare("SELECT simbolo, nombre, tipo, precio, fecha FROM precios WHERE simbolo = ?");
  $stmt->bind_param("s", $simbolo);
  $stmt->execute();
  $res = $stmt->get_result();
  $data = $res->fetch_assoc();
  
  echo json_encode($data ?: ["error" => "No existe esa moneda"]);
  exit;
}

// Si no se pide símbolo, devolvemos todos los precios
$sql = "SELECT simbolo, nombre, tipo, precio FROM precios ORDER BY tipo, simbolo";
$res = $conn->query($sql);
$lista = [];

while ($r = $res->fetch_assoc()) {
  $lista[] = $r;
}

echo json_encode($lista);
?>
