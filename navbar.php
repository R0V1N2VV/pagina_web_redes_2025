<?php
// Nota: Se asume que 'session_start()' ya fue llamado en el archivo principal que incluye este navbar.
// Solo verificamos si la variable de sesión 'usuario' existe.
$is_logged_in = isset($_SESSION['usuario']);
?>

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
            
            <?php if ($is_logged_in): ?>
            
            <a href="usuario.php" class="text-gray-300 hover:text-blue-400 transition duration-300 font-medium flex items-center border-l border-gray-700 pl-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Mi Cuenta
            </a>

            
            
         
            
            <?php else: ?>
            
            <a href="login.php" class="bg-green-500 hover:bg-green-400 px-4 py-2 rounded-xl text-white font-semibold transition duration-300 shadow-md flex items-center border-l border-gray-700 pl-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Iniciar sesión
            </a>
            
            <?php endif; ?>

        </div>
    </div>
</nav>