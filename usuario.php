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
  <title>Mi Cuenta | Cripto</title>
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
    .perfil {
      background: #141414;
      border: 2px solid #ffd700;
      border-radius: 15px;
      padding: 30px;
      width: 400px;
      text-align: center;
      box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.4);
    }
    .perfil h2 {
      color: #ffd700;
    }
    .perfil p {
      margin: 10px 0;
    }
    a {
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
    a:hover {
      background: #ffcc00;
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <div class="perfil">
    <h2>👤 Información de tu cuenta</h2>
    <p><strong>Nombre:</strong> <?php echo $usuario['nombre']; ?></p>
    <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>
    <a href="index.php">🏠 Volver al inicio</a>
    <a href="logout.php">🚪 Cerrar sesión</a>
  </div>
</body>
</html>
