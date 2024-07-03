<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Inventario</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Inter', sans-serif;
    }

    .bg {
      background-image: url('libs/images/ferreteria.jpg');
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      filter: blur(5px);
      position: absolute;
      width: 100%;
      z-index: -1;
    }

    .login-page {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 300px;
      text-align: center;
    }

    .login-container h1 {
      font-size: 24px;
      margin-bottom: 10px;
      color: #333;
    }

    .login-container p {
      font-size: 14px;
      margin-bottom: 20px;
      color: #777;
    }

    .login-container form .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .login-container label {
      font-size: 14px;
      color: #555;
      margin-bottom: 5px;
      display: block;
    }

    .login-container input {
      width: 91%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      color: #333;
    }

    .login-container .btn {
      background-color: #0056b3;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 5px;
      width: 98%;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-container .btn:hover {
      background-color: #003d80;
    }
  </style>
</head>
<body>
  <div class="bg"></div>
  <div class="login-page">
    <div class="login-container">
        <h1><b>Inicio de sesión</b></h1>
        <hr>
        <p>Ingresa tus datos para continuar</p>
        <?php echo display_msg($msg); ?>
        <form method="post" action="auth.php" class="clearfix">
            <div class="form-group">
                <label for="username" class="control-label"><b>Usuario</b></label>
                <input type="name" class="form-control" name="username" placeholder="Usuario">
            </div>
            <div class="form-group">
                <label for="password" class="control-label"><b>Contraseña</b></label>
                <input type="password" name="password" class="form-control" placeholder="Contraseña">
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Iniciar Sesión</button>
            </div>
        </form>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
</body>
</html>
