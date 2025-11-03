<?php session_start();
include_once "../../../funciones/funciones.php";

?>

<!DOCTYPE html>
<html lang="en">

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
            /* Ocupa toda la altura de la ventana */
            margin: 0;
            background-color: #f9f9f9;
            /* opcional, solo para estética */
            font-family: sans-serif;
            text-align: center;
        }

        .form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin: 10px 0;
        }

        .gui-input {
            padding: 10px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
        }

        .btn {
            margin-top: 10px;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        button {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div>
        <form class="form" action="muestra_saldo.php" method="POST" name="movil">
            <h1>EDITA SALDO A FAVOR</h1>
            <h1>Ingrese Movil</h1>
            <input type="text" name="movil" class="gui-input" autofocus>
            <br><br>
            <input type="submit" value="BUSCAR" class="btn btn-secondary">
        </form>

        <button onclick="cerrarPagina()" class="btn btn-secondary btn-sm">CERRAR PAGINA</button>
    </div>

    <?php foot() ?>
</body>

</html>