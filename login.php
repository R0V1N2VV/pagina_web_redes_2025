<?php
session_start();
include "conexion.php";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['usuario'] = $row;
            header("Location: index.php");
            exit;
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | CriptoSim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<body class="bg-gray-900 flex flex-col min-h-screen text-gray-100">
    
    <nav class="bg-gray-800 fixed w-full top-0 z-50 shadow-xl border-b border-green-500/20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center">
            
            <h1 class="text-3xl font-extrabold text-green-400 tracking-wider">Cripto Sim</h1>
            
            <div class="flex items-center space-x-4">
                
                <a href="index.php" class="text-gray-300 hover:text-green-400 transition duration-300 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Inicio
                </a>
                
                <a href="registro.php" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl text-white font-semibold transition duration-300 shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Registrarse
                </a>
                
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-28 flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full max-w-md border border-green-500/30">
            <h1 class="text-3xl font-bold text-green-400 mb-6 text-center">Iniciar Sesión</h1>

            <?php if($error): ?>
                <p class="bg-red-600 text-white p-3 rounded-lg mb-4 text-center font-semibold"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="email" name="email" placeholder="Correo electrónico" required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                <input type="password" name="password" placeholder="Contraseña" required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                
                <button type="submit" 
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-300 shadow-md">
                    Acceder
                </button>
            </form>

            <p class="text-center text-gray-400 mt-6">
                ¿No tienes una cuenta? 
                <a href="registro.php" class="text-blue-400 hover:text-blue-300 font-semibold transition">Regístrate aquí</a>
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