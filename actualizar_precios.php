<?php
header('Content-Type: application/json');

$db_host = '127.0.0.1';
$db_name = 'cripto_db';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// 1️⃣ Obtener precios actuales
$stmt = $pdo->query("SELECT simbolo, precio FROM precios");
$monedas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2️⃣ Generar pequeñas variaciones (±5%) para simular cambios
foreach ($monedas as &$m) {
    // Generar una variación en un rango de ±0.5% (más realista que ±5%)
    // Multiplicamos por 1000.0 para usar enteros en mt_rand y luego dividir por 1000.0/100
    $variacion_porcentual = (mt_rand(-50, 50) / 100.0); // Rango de -0.5% a 0.5%
    
    // Convertir a factor: 1 + (variacion / 100)
    $factor = 1 + ($variacion_porcentual / 100);
    
    $nuevo_precio = round($m['precio'] * $factor, 6);
    $cambio = round($variacion_porcentual, 2);

    $m['nuevo_precio'] = $nuevo_precio;
    $m['change_24h'] = $cambio;
}
unset($m); // Romper referencia para evitar errores

// 3️⃣ Obtener cantidad total de cada cripto en todas las billeteras
$query = $pdo->query("
    SELECT moneda AS simbolo, SUM(cantidad) AS total_cantidad
    FROM billetera
    GROUP BY moneda
");
$billeteras = $query->fetchAll(PDO::FETCH_KEY_PAIR); // simbolo => total_cantidad

// 4️⃣ Actualizar precios + market_cap y guardar histórico
$update = $pdo->prepare("UPDATE precios SET precio = ?, change_24h = ?, market_cap = ?, fecha = NOW() WHERE simbolo = ?");
$insert_hist = $pdo->prepare("INSERT INTO precios_historial (simbolo, precio, fecha) VALUES (?, ?, NOW())");

foreach ($monedas as $m) {
    $simbolo = $m['simbolo'];
    
    // Obtener la cantidad de la billetera (0 si no existe)
    $cantidad_total = $billeteras[$simbolo] ?? 0;
    
    // Calcular Market Cap
    $market_cap = round($m['nuevo_precio'] * $cantidad_total, 2);

    // 🔑 Las dos operaciones clave deben ejecutarse para CADA moneda
    
    // Actualizar precio en la tabla principal
    $update->execute([$m['nuevo_precio'], $m['change_24h'], $market_cap, $simbolo]);
    
    // Insertar nuevo punto en el historial (esto soluciona el problema del gráfico de BSV)
    $insert_hist->execute([$simbolo, $m['nuevo_precio']]);
    
    $m['market_cap'] = $market_cap;
}

echo json_encode([
    'status' => 'ok',
    'precios' => $monedas
]);