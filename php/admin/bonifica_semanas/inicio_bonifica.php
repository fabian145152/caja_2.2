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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COBROS</title>
    <?php head() ?>
    <style>
        #Power-Contenedor {
            text-align: center;
        }
    </style>
</head>

<body>
    <br><br><br>
    <h3 style="text-align: center;">BONIFICA SEMANAS</h3>
    <br><br><br><br><br>
    <form style=" text-align:center;" method="get" action="bonifica.php">
        INGRESE MOVIL:
        <input type="text" id="movil" name="movil" autofocus required>
        <button type="submit">Continuar</button>
    </form>
    <br><br><br>


    <br><br><br>

    <div id="Power-Contenedor">
        <button onclick="cerrarPagina()" class="btn btn-primary btn-sm">CERRAR PAGINA</button>
    </div>


    <?php foot(); ?>
    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>
</body>

</html>