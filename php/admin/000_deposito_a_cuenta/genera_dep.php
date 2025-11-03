<?php
session_start();
include_once "../../../funciones/funciones.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEPOSITO A CUENTA</title>
    <?php head() ?>
    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Centra verticalmente */
            align-items: center;
            /* Centra horizontalmente */
            height: 100vh;
            /* Ocupa toda la pantalla */
            margin: 0;
            text-align: center;
            background-color: #f9f9f9;
            /* opcional, estética */
            font-family: sans-serif;
        }

        .form {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        h1 {
            margin-bottom: 20px;
        }

        .gui-input {
            padding: 10px;
            width: 220px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
        }

        .btn {
            margin-top: 15px;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        button {
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <div>
        <form class="form" action="genera.php" method="POST" name="movil">
            <h1>DEPOSITOS A CUENTA</h1>
            <h1>Ingrese Móvil</h1>
            <input type="text" name="movil" class="gui-input" autofocus>
            <br><br>
            <input type="submit" value="BUSCAR" class="btn btn-secondary">
        </form>

        <button onclick="cerrarPagina()" class="btn btn-secondary btn-sm">CERRAR PÁGINA</button>
    </div>

    <?php foot() ?>
</body>

</html>