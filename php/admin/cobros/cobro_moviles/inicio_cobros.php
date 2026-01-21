<?php
session_start();

include_once "../../../../funciones/funciones.php";
include_once "semana.php";
include_once "mes.php";

if (!isset($_SESSION['uname'])) {
    // Si la sesión expiró, redirige al login
    header("Location: ../../../../login.php");
    exit;
}

$con = conexion();
$con->set_charset("utf8mb4");

$semana_actual = date("W");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobros</title>
    <?php head(); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding-top: 100px;
        }

        form {
            margin: 40px auto;
            display: inline-block;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"] {
            padding: 8px 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-left: 10px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #Power-Contenedor {
            margin-top: 60px;
        }

        #Power-Contenedor button {
            background-color: #dc3545;
        }

        #Power-Contenedor button:hover {
            background-color: #b02a37;
        }
    </style>
</head>

<body>
    <form method="POST" action="cobro_empieza.php">
        <label for="movil">Cobrar a móvil:</label>
        <input type="text" id="movil" name="movil" autofocus required>
        <button type="submit">Continuar</button>
    </form>

    <form method="POST" action="../cobro_tropas/tropas_empieza.php">
        <label for="tropa">Cobrar a tropa:</label>
        <input type="text" id="tropa" name="tropa" required>
        <button type="submit">Continuar</button>
    </form>

    <div id="Power-Contenedor">
        <button onclick="cerrarPagina()">Cerrar página</button>
    </div>

    <?php foot(); ?>

    <script>
        function cerrarPagina() {

            window.close();

        }
    </script>
</body>

</html>