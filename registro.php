<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario | Mi Cripto</title>
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

    .registro-container {
      background: #141414;
      border: 2px solid #ffd700;
      border-radius: 15px;
      padding: 30px;
      width: 350px;
      text-align: center;
      box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.4);
    }

    .registro-container h2 {
      margin-bottom: 20px;
      color: #ffd700;
      text-shadow: 0 0 8px #ffd700;
    }

    .registro-container label {
      display: block;
      margin-bottom: 5px;
      text-align: left;
      font-weight: bold;
    }

    .registro-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: none;
      border-radius: 8px;
      background: #1f1f1f;
      color: #fff;
      font-size: 14px;
      outline: none;
    }

    .registro-container input:focus {
      border: 2px solid #ffd700;
      background: #262626;
    }

    .registro-container button {
      width: 100%;
      padding: 12px;
      background: #ffd700;
      color: #000;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .registro-container button:hover {
      background: #ffcc00;
      transform: scale(1.05);
    }

    .volver {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #ffd700;
      font-weight: bold;
      transition: 0.3s;
    }

    .volver:hover {
      color: #fff;
    }

    .btc-logo {
      width: 60px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="registro-container">
    <img src="favicon.png" alt="Logo Bitcoin" class="btc-logo">
    <h2>Crear cuenta</h2>
    <form action="procesar_registro.php" method="POST">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required>

      <label for="email">Correo:</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Registrarse</button>
    </form>
    <a href="index.php" class="volver"> Volver a inicio</a>
  </div>
</body>
</html>
