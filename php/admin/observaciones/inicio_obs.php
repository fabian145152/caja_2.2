<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

$semana_actual = date("W");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR OBSERVACIONES</title>
    <?php head(); ?>

    <style>
        body {
            background: #f6f6f6;
            font-family: Arial, sans-serif;
        }

        .contenedor {
            max-width: 450px;
            margin: 60px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        h2 {
            margin-top: 0;
            font-size: 22px;
            color: #333;
        }

        .texto-secundario {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        input[type="text"] {
            padding: 8px;
            width: 120px;
            font-size: 16px;
            text-align: center;
        }

        button {
            padding: 7px 18px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-cerrar {
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <div class="contenedor">
        <h2>Editar Observaciones</h2>
        <p class="texto-secundario">Texto recordatorio del móvil. Aparece en la página "Cobrar el móvil".</p>

        <form method="post" action="ver_obs.php">
            <label for="movil">Ingrese Móvil:</label><br><br>
            <input type="text" id="movil" name="movil" autofocus required>
            <br><br>
            <button type="submit" class="btn btn-primary btn-sm">Continuar</button>
        </form>

        <button onclick="cerrarPagina()" class="btn btn-primary btn-sm btn-cerrar">Cerrar Página</button>
    </div>

    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>

    <?php foot(); ?>
</body>

</html>