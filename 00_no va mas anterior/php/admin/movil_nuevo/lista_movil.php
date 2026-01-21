<?php
session_start();
if (!isset($_SESSION['logueado']) || !$_SESSION['logueado']) {
    header("Location: login.php"); // Redirige si no está logueado
    exit;
}

include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TITULARES</title>
    <?php head(); ?>

    <script src="../../../js/jquery-3.4.1.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/bootbox.min.js"></script>

    <style>
        body {
            margin: 50px;
        }

        a {
            text-align: center;
        }
    </style>

    <script>
        function deleteProduct(cod_titular) {
            bootbox.confirm("Desea Eliminar? " + cod_titular, function(result) {
                if (result) {
                    window.location = "borrar_movil.php?q=" + cod_titular;
                }
            });
        }

        function updateProduct(cod_titular) {
            window.location = "edita_movil.php?q=" + cod_titular;
        }

        function cerrarPagina() {
            window.close();
        }
    </script>
</head>

<body>
    <h1 class="text-center" style="margin: 5px;">LISTAR TITULARES</h1>

    <div class="row mb-3">
        <div class="btn-group d-flex w-50" role="group">
            <button onclick="cerrarPagina()" class="btn btn-primary btn-sm">CERRAR PAGINA</button>
        </div>
    </div>

    <table class="table table-bordered table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Movil</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Direccion</th>
                <th>CP</th>
                <th>Celular</th>
                <th>Licencia</th>
                <th>Estado</th>
                <th>Tropa</th>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT c.*, e.nombre AS nombre_estado 
                    FROM completa c
                    LEFT JOIN estados_unidades e ON c.estado_admin = e.id
                    ORDER BY c.movil ASC";
            $result = $con->query($sql);

            while ($row = $result->fetch_assoc()) {
                $movil_formateado = str_pad($row['movil'], 4, "0", STR_PAD_LEFT);
                $tropa = ($row['tropa'] != 50) ? $row['tropa'] : " ";
            ?>
                <tr>
                    <td><?= $movil_formateado ?></td>
                    <td><?= $row['nombre_titu'] ?></td>
                    <td><?= $row['apellido_titu'] ?></td>
                    <td><?= $row['dni_titu'] ?></td>
                    <td><?= $row['direccion_titu'] ?></td>
                    <td><?= $row['cp_titu'] ?></td>
                    <td><?= $row['cel_titu'] ?></td>
                    <td><?= $row['licencia_titu'] ?></td>
                    <td><?= $row['nombre_estado'] ?? "Sin estado" ?></td>
                    <td><?= $tropa ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="updateProduct(<?= $row['id'] ?>)">
                            Actualizar
                        </button>
                    </td>
                </tr>
            <?php
            }
            foot();
            ?>
        </tbody>
    </table>
</body>

</html>