<?php
session_start();
include_once "../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

$movil = isset($_POST['movil']) ? (int)$_POST['movil'] : 0;

if ($movil <= 0) {
    die("Movil inválido.");
}

// Consulta segura para el cliente
$stmt = $con->prepare("SELECT * FROM completa WHERE movil = ?");
$stmt->bind_param("i", $movil);
$stmt->execute();
$res_comp = $stmt->get_result();
$row_comp = $res_comp->fetch_assoc();

// Consulta todos los productos
$res_venta = $con->query("SELECT * FROM productos");

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUCTOS EN VENTA</title>
    <?php head(); ?>
    <style>
        body {
            margin: 40px;
        }

        .ventas-anteriores {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h2>
        Nombre: <?= htmlspecialchars($row_comp['nombre_titu'] . " " . $row_comp['apellido_titu']) ?>
        - Movil: <?= $mov = htmlspecialchars($row_comp['movil']) ?>
    </h2>
    <h4>Venta de productos</h4>

    <form action="guarda_venta.php" method="post">
        <input type="hidden" name="movil" value="<?= $mov ?>">

        <table class="table table-bordered table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th style="text-align: center">Seleccione</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_venta = $res_venta->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row_venta['nombre']) ?></td>
                        <td style="text-align: center">
                            <input type="checkbox" name="opciones[]" value="<?= $row_venta['id'] ?>">
                        </td>
                        <td><?= htmlspecialchars($row_venta['descripcion']) ?></td>
                        <td><?= htmlspecialchars($row_venta['precio']) ?></td>
                        <td><?= htmlspecialchars($row_venta['stock']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-danger btn-sm">GUARDAR</button>
        &nbsp;&nbsp;&nbsp;
        <a href="eliminar_venta.php?q=<?= $mov ?>" class="btn btn-primary btn-sm">ELIMINAR VENTA</a>
        &nbsp;&nbsp;&nbsp;
        <a href="inicio_ventas.php" class="btn btn-primary btn-sm">VOLVER</a>
    </form>

    <div class="ventas-anteriores">
        <h2>COMPRÓ ANTERIORMENTE:</h2>
        <?php
        $ventas = array_filter([
            $row_comp['venta_1'],
            $row_comp['venta_2'],
            $row_comp['venta_3'],
            $row_comp['venta_4'],
            $row_comp['venta_5']
        ]);

        if (!empty($ventas)) {
            $ids = implode(",", array_map('intval', $ventas));
            $res_vendidos = $con->query("SELECT nombre FROM productos WHERE id IN ($ids)");
            while ($prod = $res_vendidos->fetch_assoc()) {
                echo htmlspecialchars($prod['nombre']) . "<br>";
            }
        } else {
            echo "No hay compras anteriores.";
        }
        ?>
    </div>

    <?php foot(); ?>
</body>

</html>