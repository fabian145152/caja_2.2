<?php
include_once "funciones/funciones.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingreso a Caja</title>

    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

    <?php head(); ?>

    <style>
        body {
            background-color: burlywood;
            font-family: 'Lato', sans-serif;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff8ee;
            border: 1px solid #d2b48c;
            border-radius: 15px;
            padding: 40px 50px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 380px;
        }

        h2 {
            margin-bottom: 25px;
            color: #4a3722;
            font-weight: bold;
        }

        input.form-control {
            border-radius: 8px;
            border: 1px solid #c8a97e;
            padding: 10px;
            font-size: 16px;
        }

        input.form-control:focus {
            border-color: #8b5e34;
            box-shadow: 0 0 4px rgba(139, 94, 52, 0.4);
        }

        button,
        a.btn {
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <form action="php/login.php" method="POST" accept-charset="UTF-8" class="form-signin">
            <h2 class="text-center">Ingreso a Caja</h2>

            <input type="text" class="form-control mb-3" name="username" placeholder="Usuario o E-mail" required>

            <input type="password" class="form-control mb-4" name="password" placeholder="Contraseña" required>

            <!-- Botones con colores Bootstrap -->
            <button type="submit" class="btn btn-primary btn-block mb-3">Entrar</button>

            <a href="http://google.com" class="btn btn-secondary btn-block">Salir</a>
        </form>
    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>