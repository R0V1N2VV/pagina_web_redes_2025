<?php
header("Content-Type: application/json");

// Simulación de precios dinámicos (puedes reemplazar con BD o API externa)
$precios = [
    "BTC" => rand(64000, 66000),
    "ETH" => rand(3400, 3600),
    "ADA" => rand(0.4, 0.6), 
    "XRP" => rand(0.45, 0.55)
];

echo json_encode($precios);
?>
