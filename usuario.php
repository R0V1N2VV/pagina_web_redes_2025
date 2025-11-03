<?php
session_start();

// 1. VERIFICACIÓN DE EXISTENCIA
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// 2. VERIFICACIÓN DEL TIPO DE DATO (Corrección clave)
if (!is_array($_SESSION['usuario'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// --- Lógica de la Base de Datos al inicio ---
include("conexion.php"); 
$id_usuario = $usuario['id'] ?? 0;

// Inicializar saldo con un valor por defecto
$saldo_usuario = 0.00; 

// --- VARIABLES DE FILTRO (NUEVO) ---
$filtro_tipo = $_GET['tipo'] ?? 'todos'; // Puede ser 'compra', 'venta', 'todos'
$filtro_activo = $_GET['activo'] ?? 'todos'; // Puede ser 'cripto', 'bolsa', 'todos'
$params_historial = [];
$tipos_historial = "";
// --- FIN VARIABLES DE FILTRO ---

if ($id_usuario > 0) {
    
    // 1. Obtener el SALDO
    $sql_saldo = "SELECT saldo FROM usuarios WHERE id = ?";
    $stmt_saldo = $conn->prepare($sql_saldo);
    $stmt_saldo->bind_param("i", $id_usuario);
    $stmt_saldo->execute();
    $res_saldo = $stmt_saldo->get_result();
    $saldo_row = $res_saldo->fetch_assoc();
    $saldo_usuario = $saldo_row['saldo'] ?? 0.00;
    $stmt_saldo->close();

    // 2. Obtener los activos del usuario desde la tabla 'billetera'
    $sql_billetera = "SELECT moneda, cantidad FROM billetera WHERE id_usuario = ?";
    $stmt_billetera = $conn->prepare($sql_billetera);
    $stmt_billetera->bind_param("i", $id_usuario);
    $stmt_billetera->execute();
    $res_billetera = $stmt_billetera->get_result();

    $billetera = [];
    while($row = $res_billetera->fetch_assoc()) {
        $moneda = $row['moneda'];
        $cantidad = floatval($row['cantidad']);
        if (!isset($billetera[$moneda])) {
            $billetera[$moneda] = 0;
        }
        $billetera[$moneda] += $cantidad;
    }
    $stmt_billetera->close();

    // 3. Obtener el Historial de transacciones (APLICANDO FILTROS)
    $sql_historial = "SELECT moneda, cantidad, precio, tipo, fecha FROM historial WHERE id_usuario = ?";
    
    $tipos_historial = "i";
    $params_historial[] = $id_usuario;

    // Aplicar filtro por TIPO (compra/venta)
    if ($filtro_tipo !== 'todos') {
        $sql_historial .= " AND tipo = ?";
        $tipos_historial .= "s";
        $params_historial[] = $filtro_tipo;
    }

    // Aplicar filtro por ACTIVO (cripto/bolsa) - Se asume que tu columna 'moneda' tiene la información
    // Esta lógica requiere que la columna 'moneda' o una columna adicional indique si es cripto o bolsa.
    // DADA LA ESTRUCTURA DE TU BD, ASUMIREMOS ESTA LÓGICA DE EJEMPLO:
    // Las criptomonedas se identifican por símbolos como BTC, ETH, SOL (3 letras).
    // Las acciones de bolsa se identifican por un símbolo, ej. AAPL, TSLA, GOOGL.
    
    // Si necesitas un filtro 100% exacto, deberías agregar una columna 'tipo_activo' (ej: 'CRIPTOMONEDA', 'BOLSA') 
    // a la tabla 'historial' o 'billetera'. Usaremos un ejemplo basado en la longitud por ahora.
    if ($filtro_activo !== 'todos') {
        if ($filtro_activo === 'cripto') {
             // Ejemplo: Monedas de 3 letras (ej: BTC, ETH) - Esto es una suposición.
             $sql_historial .= " AND LENGTH(moneda) <= 4"; 
        } elseif ($filtro_activo === 'bolsa') {
             // Ejemplo: Activos de más de 4 letras (ej: AAPL, TSLA, GME) - Esto es una suposición.
             $sql_historial .= " AND LENGTH(moneda) > 4"; 
        }
    }
    
    // Finalizar la consulta
    $sql_historial .= " ORDER BY fecha DESC";
    
    // Preparar la consulta con los parámetros dinámicos
    $stmt_historial = $conn->prepare($sql_historial);
    
    // Ejecutar la función bind_param con los tipos y los parámetros
    if (!empty($params_historial)) {
        $stmt_historial->bind_param($tipos_historial, ...$params_historial);
    }
    
    $stmt_historial->execute();
    $res_historial = $stmt_historial->get_result();
    // NOTA: $res_historial se usará más tarde en la sección HTML
} 
// --- Fin de la Lógica de la Base de Datos ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta | CriptoSim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <nav class="bg-gray-800 fixed w-full top-0 z-50 shadow-xl border-b border-green-500/20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-3xl font-extrabold text-green-400 tracking-wider">Cripto Sim</h1>
            <div class="flex items-center space-x-4">
                <a href="index.php" class="text-gray-300 hover:text-green-400 transition duration-300 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Inicio
                </a>
                <a href="criptomonedas.php" class="text-gray-300 hover:text-purple-400 transition duration-300 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.01 12.01 0 002.932 12c0 3.072 1.258 5.857 3.24 7.962l4.8 4.8a1 1 0 001.414 0l4.8-4.8c1.982-2.105 3.24-4.89 3.24-7.962a12.01 12.01 0 00-1.07-5.056z"></path></svg>
                    Criptomonedas
                </a>
                <a href="bolsa.php" class="text-gray-300 hover:text-yellow-400 transition duration-300 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v2m8-2v2m-8 4v2m8-4v2m4-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Bolsa
                </a>
                <a href="usuario.php" class="text-gray-300 hover:text-blue-400 transition duration-300 font-medium flex items-center border-l border-gray-700 pl-4">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Mi Cuenta
                </a>
                <a href="logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl text-white font-semibold transition duration-300 shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar sesión
                </a>
            </div>
        </div>
    </nav>
    <main class="flex-grow pt-28 pb-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <header class="mb-10 flex items-center justify-between border-b border-gray-700 pb-4">
                <h1 class="text-4xl font-extrabold text-white flex items-center">
                    <img src="favicon.png" alt="Logo CriptoSim" class="w-8 h-8 mr-3 opacity-80">
                    Panel de Cuenta
                </h1>
            </header>

            <?php if ($id_usuario > 0): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-8">
                    
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl border border-green-500/30 card">
                        <h2 class="text-xl font-bold text-green-400 mb-4 border-b border-gray-700 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Detalles de Usuario
                        </h2>
                        <div class="space-y-3">
                            <p class="text-lg"><span class="font-semibold text-gray-400">Nombre:</span> <span class="text-white ml-2"><?php echo htmlspecialchars($usuario['nombre'] ?? 'N/A'); ?></span></p>
                            <p class="text-lg"><span class="font-semibold text-gray-400">Correo:</span> <span class="text-white ml-2"><?php echo htmlspecialchars($usuario['email'] ?? 'N/A'); ?></span></p>
                            
                            <p class="text-lg border-t border-gray-700 pt-3 flex justify-between items-center">
                                <span class="font-semibold text-yellow-400">Saldo :</span> 
                                <span class="text-2xl font-bold text-green-300 font-mono">$<?php echo htmlspecialchars(number_format($saldo_usuario, 2)); ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl border border-blue-500/30 card">
                        <h2 class="text-xl font-bold text-blue-400 mb-4 border-b border-gray-700 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Mis Activos (Billetera)
                        </h2>
                        <?php if(count($billetera) > 0): ?>
                            <ul class="space-y-3">
                                <?php foreach($billetera as $moneda => $total): if($total > 0): ?>
                                    <li class="bg-gray-700 hover:bg-gray-600 rounded-lg px-4 py-2 flex justify-between items-center transition duration-300">
                                        <span class="font-bold text-yellow-300 text-base"><?php echo htmlspecialchars($moneda); ?></span>
                                        <span class="text-lg font-mono text-green-300"><?php echo number_format($total, 4); ?></span>
                                    </li>
                                <?php endif; endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-gray-400 text-center py-4">No tienes activos en la billetera. ¡Empieza a invertir!</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl border border-yellow-500/30">
                        <h2 class="text-xl font-bold text-yellow-400 mb-4 border-b border-gray-700 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Historial de Transacciones
                        </h2>

                        <form method="GET" action="usuario.php" class="mb-4 flex flex-wrap gap-4 items-end">
                            <div>
                                <label for="filtro_tipo" class="block text-sm font-medium text-gray-400 mb-1">Tipo de Transacción</label>
                                <select name="tipo" id="filtro_tipo" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                    <option value="todos" <?php echo ($filtro_tipo === 'todos') ? 'selected' : ''; ?>>Todos</option>
                                    <option value="compra" <?php echo ($filtro_tipo === 'compra') ? 'selected' : ''; ?>>Compra</option>
                                    <option value="venta" <?php echo ($filtro_tipo === 'venta') ? 'selected' : ''; ?>>Venta</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="filtro_activo" class="block text-sm font-medium text-gray-400 mb-1">Tipo de Activo</label>
                                <select name="activo" id="filtro_activo" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                    <option value="todos" <?php echo ($filtro_activo === 'todos') ? 'selected' : ''; ?>>Todos</option>
                                    <option value="cripto" <?php echo ($filtro_activo === 'cripto') ? 'selected' : ''; ?>>Criptomonedas</option>
                                    <option value="bolsa" <?php echo ($filtro_activo === 'bolsa') ? 'selected' : ''; ?>>Bolsa</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 shadow-md">
                                Aplicar Filtros
                            </button>
                            
                            <a href="usuario.php" class="bg-red-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 shadow-md">
                                Limpiar
                            </a>
                        </form>
                        <?php if(isset($res_historial) && $res_historial->num_rows > 0): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm bg-gray-700/50 rounded-lg overflow-hidden">
                                    <thead class="bg-gray-700 text-gray-300 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Activo</th>
                                            <th class="px-4 py-2 text-right">Cantidad</th>
                                            <th class="px-4 py-2 text-right">Precio Un.</th>
                                            <th class="px-4 py-2 text-center">Tipo</th>
                                            <th class="px-4 py-2 text-center">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $res_historial->fetch_assoc()): 
                                            $tipo_clase = ($row['tipo'] === 'compra') ? 'bg-green-800/20 text-green-300' : 'bg-red-800/20 text-red-300';
                                        ?>
                                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                                                <td class="px-4 py-2 font-semibold"><?php echo htmlspecialchars($row['moneda']); ?></td>
                                                <td class="px-4 py-2 text-right font-mono"><?php echo htmlspecialchars(number_format($row['cantidad'], 6)); ?></td>
                                                <td class="px-4 py-2 text-right text-gray-400 font-mono">$<?php echo htmlspecialchars(number_format($row['precio'], 2)); ?></td>
                                                <td class="px-4 py-2 text-center">
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $tipo_clase; ?>">
                                                        <?php echo htmlspecialchars(ucfirst($row['tipo'])); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-center text-gray-500 text-xs"><?php echo htmlspecialchars($row['fecha']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-center py-4">No se encontraron transacciones con los filtros seleccionados.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
                <p class="text-red-400 mt-8 text-center bg-gray-800 p-4 rounded-lg">Error: No se pudo obtener el ID de usuario. Por favor, <a href="logout.php" class="text-blue-400 hover:text-blue-300 font-semibold">cierra la sesión</a> y vuelve a iniciarla.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 mt-auto">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 text-center text-gray-500 text-sm">
            © 2025 Cripto Sim. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>