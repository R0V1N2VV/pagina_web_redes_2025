<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "cripto_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Verificar si el correo ya existe
$check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

$resultado = "";
$tipo = "";

if ($check->num_rows > 0) {
    $resultado = "⚠ El correo ya está registrado";
    $tipo = "error";
} else {
    $sql = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $nombre, $email, $pass);

    if ($sql->execute()) {
        $resultado = "✅ Registro exitoso, bienvenido a la comunidad cripto 🚀";
        $tipo = "exito";
    } else {
        $resultado = "❌ Error al registrar: " . $sql->error;
        $tipo = "error";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultado Registro | CriptoSim</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="favicon.png" type="image/png">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
  <!-- Navbar -->
  <nav class="bg-gray-800 fixed w-full top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-400">Cripto Sim</h1>
      <div class="flex items-center space-x-3">
        <a href="index.php" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white font-semibold">🏠 Inicio</a>
        <a href="registro.php" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-white font-semibold">📝 Registrarse</a>
      </div>
    </div>
  </nav>

  <main class="flex-grow pt-24 flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md text-center">
      <img src="favicon.png" alt="Bitcoin" class="mx-auto w-16 mb-4">
      <h2 class="text-2xl font-bold mb-4 <?php echo ($tipo == 'exito') ? 'text-green-400' : 'text-red-400'; ?>">
        <?php echo ($tipo == "exito") ? "¡Registro Exitoso!" : "Ocurrió un problema"; ?>
      </h2>
      <p class="mb-6 <?php echo ($tipo == 'exito') ? 'text-green-300' : 'text-red-300'; ?>">
        <?php echo $resultado; ?>
      </p>
      <div class="space-y-3">
        <a href="index.php" class="block w-full bg-green-500 hover:bg-green-600 px-4 py-2 rounded font-semibold transition">Ir al inicio</a>
        <a href="registro.php" class="block w-full bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded font-semibold transition">Volver a registro</a>
      </div>
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-400 text-center py-4 mt-6 rounded">
    <p>© 2025 CriptoSim | Desarrollado por Felipe Mochi </p>
  </footer>
</body>
</html>
 
  </style>
</head>
<body>
  <div class="mensaje <?php echo $tipo; ?>">
    <img src="favicon.png" alt="Bitcoin" class="btc-logo">
    <h2><?php echo ($tipo == "exito") ? "¡Registro Exitoso!" : "Ocurrió un problema"; ?></h2>
    <p><?php echo $resultado; ?></p>
    <a href="index.php" class="btn"> Ir al inicio</a>
    <br><br>
    <a href="registro.php" class="btn"> Volver a registro</a>
  </div>
</body>
</html>
