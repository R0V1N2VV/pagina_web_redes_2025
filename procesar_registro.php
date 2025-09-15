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
<head>
  <meta charset="UTF-8">
  <title>Resultado Registro | Cripto</title>
  <link rel="icon" href="favicon.png" type="image/png">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
      color: #f1f1f1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .mensaje {
      background: #141414;
      border: 2px solid #ffd700;
      border-radius: 15px;
      padding: 30px;
      width: 400px;
      text-align: center;
      box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.4);
      animation: fadeIn 0.8s ease-in-out;
    }
    .mensaje h2 {
      color: #ffd700;
      text-shadow: 0 0 8px #ffd700;
    }
    .mensaje p {
      font-size: 16px;
      margin-top: 15px;
    }
    .exito p {
      color: #00ff88;
    }
    .error p {
      color: #ff4444;
    }
    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 20px;
      background: #ffd700;
      color: #000;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn:hover {
      background: #ffcc00;
      transform: scale(1.05);
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    .btc-logo {
      width: 60px;
      margin-bottom: 15px;
    }
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
