<?php
session_start();

// ------------------ CONEXIÓN A LA DB ------------------
$servername = "localhost";
$username = "root";
$password = ""; // contraseña por defecto de XAMPP
$dbname = "criptosim"; // cambia por tu nombre de base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ------------------ LOGIN ------------------
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

        // Verifica contraseña (asumiendo que usaste password_hash)
        if(password_verify($password, $row['password'])){
            $_SESSION['usuario'] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'email' => $row['email']
            ];
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
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-gray-100">

<div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-green-400 mb-6 text-center">CriptoSim 🔐 Login</h1>

    <?php if($error): ?>
        <p class="bg-red-600 text-white p-2 rounded mb-4 text-center"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <input type="email" name="email" placeholder="Correo electrónico" required
               class="w-full px-4 py-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400">
        <input type="password" name="password" placeholder="Contraseña" required
               class="w-full px-4 py-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-400">
        <button type="submit" 
                class="w-full bg-green-500 hover:bg-green-600 px-4 py-2 rounded font-semibold transition">Iniciar sesión</button>
    </form>

    <p class="mt-4 text-center text-gray-400">
        ¿No tienes cuenta? <a href="registro.php" class="text-yellow-400 hover:underline">Regístrate</a>
    </p>
</div>

</body>
</html>
