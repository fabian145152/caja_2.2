<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

// Obtener móvil por POST o GET
$movil = isset($_POST['movil']) ? $_POST['movil'] : (isset($_GET['movil']) ? $_GET['movil'] : null);

// Traer datos
$sql_obs = "SELECT * FROM completa WHERE movil=" . intval($movil);
$result_obs = $con->query($sql_obs);
$row_obs = $result_obs->fetch_assoc();

$observaciones  = $row_obs['obs'];
$nombre_titu    = $row_obs['nombre_titu'];
$apellido_titu  = $row_obs['apellido_titu'];
$movil          = $row_obs['movil'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Observaciones</title>
    <?php head(); ?>

    <style>
        body {
            background: #f6f6f6;
            font-family: Arial, sans-serif;
        }

        .contenedor {
            max-width: 700px;
            margin: 40px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            color: #333;
        }

        .descripcion {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        form {
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 6px;
            width: 180px;
            text-align: center;
            margin-bottom: 15px;
        }

        textarea {
            width: 90%;
            margin-top: 10px;
            font-size: 15px;
            padding: 10px;
        }

        .btn {
            padding: 7px 20px;
            cursor: pointer;
            font-size: 15px;
        }

        .cerrar {
            display: block;
            margin: 25px auto 0;
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <h2>Editar Observaciones</h2>
        <p class="descripcion">Este texto se muestra en la página <b>Cobrar Móvil</b>.</p>

        <form action="guarda_obs.php" method="POST">

            <label>Unidad:</label><br>
            <input type="text" name="nombre" value="<?php echo $movil; ?>" readonly><br><br>

            <label>Titular:</label><br>
            <input type="text" value="<?php echo $nombre_titu . ' ' . $apellido_titu; ?>" readonly style="width:300px;"><br><br>

            <label for="comentarios">Observaciones:</label><br>
            <textarea id="comentarios" name="comentarios" rows="10" required><?php echo $observaciones; ?></textarea>
            <br><br>

            <button type="submit" class="btn btn-primary btn-sm">Guardar Cambios</button>
        </form>

        <button onclick="cerrarPagina()" class="btn btn-primary btn-sm cerrar">Cerrar Página</button>

    </div>

    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>

    <?php foot(); ?>

</body>

</html>