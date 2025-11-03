<?php
session_start();
include_once "../../../funciones/funciones.php";
$_SESSION['uname'];
$_SESSION['time'];

$con = conexion();
$con->set_charset("utf8mb4");
$semana_actual = date("W");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BONIFICA SEMANAS</title>
    <?php head(); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h3 {
            color: #4a4a4a;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        form {
            background: white;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 200px;
            margin-left: 10px;
            outline: none;
            transition: all 0.3s;
        }

        form input[type="text"]:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 6px rgba(108, 99, 255, 0.3);
        }

        form button {
            background: #6c63ff;
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 18px;
            margin-left: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        form button:hover {
            background: #574bff;
        }

        #Power-Contenedor {
            text-align: center;
            margin-top: 40px;
        }

        #Power-Contenedor button {
            background: #888;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        #Power-Contenedor button:hover {
            background: #666;
        }
    </style>
</head>

<body>
    <h3>BONIFICA SEMANAS</h3>

    <form method="get" action="bonifica.php">
        <label for="movil">Ingrese Móvil:</label>
        <input type="text" id="movil" name="movil" autofocus required>
        <button type="submit">Continuar</button>
    </form>

    <div id="Power-Contenedor">
        <button onclick="cerrarPagina()" class="btn btn-success btn-sm">Cerrar Página</button>
    </div>

    <?php foot(); ?>

    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>
</body>

</html>