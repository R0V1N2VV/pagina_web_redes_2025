<!DOCTYPE html>
<html lang="es">
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario | CriptoSim</title>
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
        <a href="login.php" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg text-white font-semibold">🔑 Iniciar sesión</a>
      </div>
    </div>
  </nav>

  <main class="flex-grow pt-24 flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
      <h1 class="text-2xl font-bold text-green-400 mb-6 text-center">CriptoSim 🪙 Registro</h1>
      <form action="procesar_registro.php" method="POST" class="space-y-4">
        <input type="text" name="nombre" placeholder="Nombre completo" required
               class="w-full px-4 py-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400">
        <input type="email" name="email" placeholder="Correo electrónico" required
               class="w-full px-4 py-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400">
        <input type="password" name="password" placeholder="Contraseña" required
               class="w-full px-4 py-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400">
        <button type="submit" 
                class="w-full bg-green-500 hover:bg-green-600 px-4 py-2 rounded font-semibold transition">
          Registrarse
        </button>
      </form>
      <p class="mt-4 text-center text-gray-400">
        ¿Ya tienes cuenta? <a href="login.php" class="text-yellow-400 hover:underline">Inicia sesión</a>
      </p>
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-400 text-center py-4 mt-6 rounded">
    <p>© 2025 CriptoSim | Desarrollado por Felipe Mochi </p>
  </footer>
</body>
</html>

 