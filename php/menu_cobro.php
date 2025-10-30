<?php

session_start();
include_once "../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

if ($_SESSION['logueado']) {

    echo '<h4>' . "BIENVENIDO "  . $_SESSION['uname'] . " Administrador..." . '</h4>';

    $_SESSION['time'] . '<br>';

    $nombre = $_SESSION['uname'];

    $fecha = date('Y-m-d');
    $abre = $_SESSION['time'];

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $usuario_logeado = "INSERT INTO `users_logeado`(nombre, fecha, abre) VALUES ('$nombre', '$fecha', '$abre')";

    if ($con->query($usuario_logeado) === TRUE) {
    } else {
        echo "Error: " . $usuario_logeado . "<br>" . $con->error;
    }
    $semana_actual = date('W');

?>
    <!DOCTYPE html>
    <html lang="es">

    <hea>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MENU PRINCIPAL</title>
        <link rel="icon" href="../imagenes/favicon.ico" type="image/x-icon">
        <?php head(); ?>
    </hea>

    <body>
        <h4>SEMANA: <?php echo $semana_actual ?></h4>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                    <ul class="list-group">

                        <h3>VOUCHER</h3>

                        <li> <a href="admin/voucher/inicio_voucher.php" target="_blank" class="btn btn-primary btn-block btn-sm">VOUCHER DE CAJA</a></li>
                        <br>
                        <li> <a href="admin/voucher_manual/inicio_v_manual.php" target="_blank" class="btn btn-primary btn-block btn-sm">CARGAR VOUCHER MANUALMENTE</a></li>
                        <br>
                        <h3>VENTAS</h3>
                        <li><a href="admin/venta/venta_prod.php" class=" btn btn-primary btn-block btn-sm" target="__blank">STOCK DE PRODUCTOS</a></li>
                        <br>
                        <li><a href="admin/ventas/inicio_ventas.php" class=" btn btn-primary btn-block btn-sm" target="__blank">VENTA</a></li>

                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-group">
                        <h3>MENU CAJA</h3>
                        <li><a href="admin/cobros/cobro_moviles/inicio_cobros.php" target="_blank" class=" btn btn-primary btn-block btn-sm">COBRAR MOVILES / TROPAS</a></li>
                        <br>
                        <li><a href="admin/deposito_a_cuenta/genera_dep.php" class="btn btn-secondary btn-block btn-sm" target="__blank">DEPOSITO A CUENTA DE LOS MOVILES</a></li>
                        <br>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        <div id="Power-Contenedor">
            <a href="salir.php" class="btn btn-danger btn-lg ">Salir</a>
        </div>
        <br><br><br>
    <?php
    foot();
} else {
    header("location:../index.php");
}
    ?>
    </body>

    </html>