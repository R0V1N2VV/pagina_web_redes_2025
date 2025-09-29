<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Cuenta | CriptoSim</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="favicon.png">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
  <!-- Navbar -->
  <nav class="bg-gray-800 fixed w-full top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-400">Cripto Sim</h1>
      <div class="flex items-center space-x-3">
        <a href="index.php" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white font-semibold">🏠 Inicio</a>
        <a href="logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white font-semibold">🚪 Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <main class="flex-grow pt-24 flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md text-center">
      <div class="flex flex-col items-center mb-6">
        <img src="favicon.png" alt="Logo CriptoSim" class="w-16 mb-3">
        <h1 class="text-2xl font-bold text-green-400">👤 Mi Cuenta</h1>
      </div>

      <div class="space-y-3 mb-6">
        <p><span class="font-semibold text-yellow-400">Nombre:</span> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p><span class="font-semibold text-yellow-400">Correo:</span> <?php echo htmlspecialchars($usuario['email']); ?></p>
      </div>

      <!-- Billetera/resumen de activos -->
      <?php
      include("conexion.php");
      $id_usuario = $usuario['id'];

      // Obtener los activos del usuario desde la tabla 'billetera'
      $sql = "SELECT moneda, cantidad FROM billetera WHERE id_usuario = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id_usuario);
      $stmt->execute();
      $res = $stmt->get_result();

      $billetera = [];
      while($row = $res->fetch_assoc()) {
          $moneda = $row['moneda'];
          $cantidad = floatval($row['cantidad']);
          if (!isset($billetera[$moneda])) {
              $billetera[$moneda] = 0;
          }
          $billetera[$moneda] += $cantidad;
      }
      ?>
      <div class="mb-8">
        <h2 class="text-lg font-bold text-blue-400 mb-2">Billetera</h2>
        <?php if(count($billetera) > 0): ?>
          <ul class="text-left space-y-1">
            <?php foreach($billetera as $moneda => $total): if($total > 0): ?>
              <li class="bg-gray-700 rounded px-3 py-1 flex justify-between items-center">
                <span class="font-semibold text-yellow-300"><?php echo htmlspecialchars($moneda); ?></span>
                <span class="text-green-300"><?php echo htmlspecialchars($total); ?></span>
              </li>
            <?php endif; endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-400">No tienes activos en la billetera.</p>
        <?php endif; ?>
      </div>

      <!-- Historial de compras/ventas -->
      <?php
      $sql2 = "SELECT moneda, cantidad, precio, tipo, fecha FROM historial WHERE id_usuario = ? ORDER BY fecha DESC";
      $stmt2 = $conn->prepare($sql2);
      $stmt2->bind_param("i", $id_usuario);
      $stmt2->execute();
      $res2 = $stmt2->get_result();
      ?>
      <div class="mb-8">
        <h2 class="text-lg font-bold text-green-300 mb-2">Historial de transacciones</h2>
        <?php if($res2->num_rows > 0): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-sm bg-gray-700 rounded">
              <thead>
                <tr>
                  <th class="px-2 py-1">Activo</th>
                  <th class="px-2 py-1">Cantidad</th>
                  <th class="px-2 py-1">Precio</th>
                  <th class="px-2 py-1">Tipo</th>
                  <th class="px-2 py-1">Fecha</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = $res2->fetch_assoc()): ?>
                  <tr>
                    <td class="px-2 py-1"><?php echo htmlspecialchars($row['moneda']); ?></td>
                    <td class="px-2 py-1"><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td class="px-2 py-1">$<?php echo htmlspecialchars($row['precio']); ?></td>
                    <td class="px-2 py-1"><?php echo htmlspecialchars($row['tipo']); ?></td>
                    <td class="px-2 py-1"><?php echo htmlspecialchars($row['fecha']); ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-400">No tienes transacciones registradas.</p>
        <?php endif; ?>
      </div>

      <a href="index.php" class="block w-full bg-green-500 hover:bg-green-600 px-4 py-2 rounded font-semibold transition mb-2">🏠 Ir al inicio</a>
      <a href="logout.php" class="block w-full bg-red-500 hover:bg-red-600 px-4 py-2 rounded font-semibold transition">🚪 Cerrar sesión</a>
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-400 text-center py-4 mt-6 rounded">
    <p>© 2025 CriptoSim | Desarrollado por Felipe Mochi </p>
  </footer>
</body>
</html>
