<?php
session_start();
// Aunque el registro no suele requerir una sesión, se incluye session_start()
// por si el flujo de tu aplicación lo necesita.
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario | CriptoSim</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="favicon.png">
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
                
                <a href="login.php" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl text-white font-semibold transition duration-300 shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Iniciar sesión
                </a>
                
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-28 flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full max-w-md border border-green-500/30">
            <h1 class="text-3xl font-bold text-green-400 mb-6 text-center"> Registrarse</h1>
            
            <?php 
            // Esto es un placeholder. En un entorno real, aquí se mostrarían errores de registro
            if (isset($_GET['error'])): ?>
                <p class="bg-red-600 text-white p-3 rounded-lg mb-4 text-center font-semibold">Error al registrar: <?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

            <form action="procesar_registro.php" method="POST" class="space-y-4">
                <input type="text" name="nombre" placeholder="Nombre completo" required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                <input type="email" name="email" placeholder="Correo electrónico" required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                <input type="password" name="password" placeholder="Contraseña" required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                
                <button type="submit" 
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-300 shadow-md">
                    Crear Cuenta
                </button>
            </form>

            <p class="text-center text-gray-400 mt-6">
                ¿Ya tienes una cuenta? 
                <a href="login.php" class="text-blue-400 hover:text-blue-300 font-semibold transition">Inicia sesión</a>
            </p>
        </div>
    </main>
    
    <footer class="bg-gray-800 border-t border-gray-700 mt-auto">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 text-center text-gray-500 text-sm">
            © 2025 Cripto Sim.
        </div>
    </footer>
</body>
</html>