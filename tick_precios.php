<?php
// tick_precios.php
// Aplica una variación porcentual aleatoria a TODOS los símbolos en la tabla `precios`
// y guarda el nuevo valor en la misma tabla (sobrescribiendo precio y fecha).
// NO usa asset_prices. Pensado para ejecutarse por cron/Task Scheduler o manualmente.
//
// Parámetros opcionales (GET o POST):
// - vol=0.01      -> volatilidad base (1%)
// - vol_BTC=0.02  -> volatilidad específica por símbolo (2%)
// - min=0.00000001 -> piso mínimo para evitar 0 o negativos
//
// Ejemplos:
//   php tick_precios.php
//   curl -s 'http://localhost/tick_precios.php?vol=0.008'
//   curl -s 'http://localhost/tick_precios.php?vol=0.01&vol_BTC=0.03'
//
header("Content-Type: application/json; charset=utf-8");
date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once __DIR__ . '/conexion.php'; // Debe exponer $conn (mysqli)

if (!isset($conn) || !($conn instanceof mysqli)) {
  http_response_code(500);
  echo json_encode(["ok"=>false, "error"=>"No hay conexión mysqli $conn"]);
  exit;
}

function get_param($key, $default) {
  if (isset($_GET[$key])) return $_GET[$key];
  if (isset($_POST[$key])) return $_POST[$key];
  return $default;
}

$vol_base = floatval(get_param('vol', 0.01)); // 1%
$min_val  = floatval(get_param('min', 0.00000001));

// Traer todos los símbolos actuales
$res = $conn->query("SELECT simbolo, precio FROM precios ORDER BY simbolo");
if (!$res) {
  http_response_code(500);
  echo json_encode(["ok"=>false, "error"=>"Error consultando precios: ".$conn->error]);
  exit;
}

$updated = [];
$errors  = [];

$conn->begin_transaction();
try {
  while ($row = $res->fetch_assoc()) {
    $sym = $row['simbolo'];
    $px  = floatval($row['precio']);

    // volatilidad específica por símbolo (parámetro vol_{SIMBOLO})
    $key = 'vol_' . $sym;
    $vol = isset($_GET[$key]) ? floatval($_GET[$key]) :
           (isset($_POST[$key]) ? floatval($_POST[$key]) : $vol_base);

    // Random walk: cambio porcentual uniformemente distribuido en [-vol, +vol]
    $delta = ((mt_rand() / mt_getrandmax()) * 2 - 1) * $vol; // [-vol, +vol]
    $nx = $px * (1.0 + $delta);

    if (!is_finite($nx) || $nx <= 0) $nx = $min_val;

    // Redondeo razonable (6 decimales); podés ajustar a 8 si querés más precisión
    $nx = round($nx, 6);

    // Actualizar
    $stmt = $conn->prepare("UPDATE precios SET precio=?, fecha=NOW() WHERE simbolo=?");
    if (!$stmt) { $errors[] = "Prepare falla para $sym: ".$conn->error; continue; }
    $stmt->bind_param("ds", $nx, $sym);
    if (!$stmt->execute()) { $errors[] = "Exec falla para $sym: ".$stmt->error; continue; }

    $updated[] = ["simbolo"=>$sym, "old"=>$px, "new"=>$nx, "pct"=>round($delta*100, 4)];
  }

  if (count($errors) > 0) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["ok"=>false, "error"=>"Rollback por errores", "detalles"=>$errors], JSON_UNESCAPED_UNICODE);
    exit;
  }

  $conn->commit();
  echo json_encode(["ok"=>true, "count"=>count($updated), "items"=>$updated], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  $conn->rollback();
  http_response_code(500);
  echo json_encode(["ok"=>false, "error"=>"Excepción: ".$e->getMessage()]);
}
