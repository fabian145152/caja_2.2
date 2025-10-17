<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

$movil = $_POST['movil'];

$sql_comp = "SELECT * FROM completa WHERE movil = $movil";
$res_comp = $con->query($sql_comp);
$row_comp = $res_comp->fetch_assoc();


$sql_venta = "SELECT * FROM productos WHERE 1";
$res_venta = $con->query($sql_venta);

?>
<!DOCTYPE html>
<html lang="en-es">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PRODUCTOS EN VENTA</title>
<?php head() ?>
<style>
    body {
        margin: 40px;
    }
</style>
</head>

<body>
    <h2>
        Nombre: <?= $row_comp['nombre_titu'] . " " .  $row_comp['apellido_titu'] ?>
        Movil: <?= $mov = $row_comp['movil'] ?>
    </h2>
    <h4>Venta de productos.</h4>

    <form action="guarda_venta.php" method="post">
        <input type="hidden" name="movil" value="<?= $mov ?>">

        <table class="table table-bordered table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th style="text-align: center">Seleccione</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_venta = $res_venta->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row_venta['id'] ?></td>
                        <td><?= $row_venta['nombre'] ?></td>
                        <td><?= $row_venta['descripcion'] ?></td>
                        <td><?= $row_venta['precio'] ?></td>
                        <td><?= $row_venta['stock'] ?></td>
                        <td style="text-align: center">
                            <input type="checkbox" name="opciones[]" value="<?= $row_venta['id'] ?>">
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-danger btn-sm">GUARDAR</button>
        &nbsp;&nbsp;&nbsp;
        <a href="eliminar_venta.php?q=<?= $mov ?>" class="btn btn-primary btn-sm">ELIMINAR VENTA</a>
        &nbsp;&nbsp;&nbsp;
        <a href="inicio_ventas.php" class="btn btn-primary btn-sm">VOLVER</a>
    </form>
</body>

<div>
    <h2>COMPRO ANTERIORMENTE: </h2>

    <?php

    $venta_1 = $row_comp['venta_1'];
    $venta_2 = $row_comp['venta_2'];
    $venta_3 = $row_comp['venta_3'];
    $venta_4 = $row_comp['venta_4'];
    $venta_5 = $row_comp['venta_5'];

    if ($venta_1 != 0) {
        $sql_vendido_1 = "SELECT * FROM productos WHERE id=" . $venta_1;
        $res_vendido_1 = $con->query($sql_vendido_1);
        $sql_vendido_1 = $res_vendido_1->fetch_assoc();
        echo $sql_vendido_1['nombre'];
        echo "<br>";
    }
    if ($venta_2 != 0) {
        $sql_vendido_2 = "SELECT * FROM productos WHERE id=" . $venta_2;
        $res_vendido_2 = $con->query($sql_vendido_2);
        $sql_vendido_2 = $res_vendido_2->fetch_assoc();
        echo $sql_vendido_2['nombre'];
        echo "<br>";
    }
    if ($venta_3 != 0) {
        $sql_vendido_3 = "SELECT * FROM productos WHERE id=" . $venta_3;
        $res_vendido_3 = $con->query($sql_vendido_3);
        $sql_vendido_3 = $res_vendido_3->fetch_assoc();
        echo $sql_vendido_3['nombre'];
        echo "<br>";
    }
    if ($venta_4 != 0) {
        $sql_vendido_4 = "SELECT * FROM productos WHERE id=" . $venta_4;
        $res_vendido_4 = $con->query($sql_vendido_4);
        $sql_vendido_4 = $res_vendido_4->fetch_assoc();
        echo $sql_vendido_4['nombre'];
        echo "<br>";
    }
    if ($venta_5 != 0) {
        $sql_vendido_5 = "SELECT * FROM productos WHERE id=" . $venta_5;
        $res_vendido_5 = $con->query($sql_vendido_5);
        $sql_vendido_5 = $res_vendido_5->fetch_assoc();
        echo $sql_vendido_5['nombre'];
        echo "<br>";
    }


    ?>
</div>

<?php foot(); ?>
</body>

</html>