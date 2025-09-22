<?php
session_start();

// Si no hay sesión activa, redirigir a login
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
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-gray-100">

  <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md text-center">
    <!-- Logo y título -->
    <div class="flex flex-col items-center mb-6">
      <img src="favicon.png" alt="Logo CriptoSim" class="w-16 mb-3">
      <h1 class="text-2xl font-bold text-green-400">👤 Mi Cuenta</h1>
    </div>

    <!-- Info del usuario -->
    <div class="space-y-3">
      <p><span class="font-semibold text-yellow-400">Nombre:</span> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
      <p><span class="font-semibold text-yellow-400">Correo:</span> <?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>

    <!-- Botones -->
    <div class="mt-6 flex flex-col space-y-3">
      <a href="index.php" class="w-full bg-green-500 hover:bg-green-600 px-4 py-2 rounded font-semibold transition">
        🏠 Ir al inicio
      </a>
      <a href="logout.php" class="w-full bg-red-500 hover:bg-red-600 px-4 py-2 rounded font-semibold transition">
        🚪 Cerrar sesión
      </a>
    </div>
  </div>

</body>
</html>
