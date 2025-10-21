<?php
include "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");
?>
<!DOCTYPE html>
<html lang="en-es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TROPAS</title>
    <?php head(); ?>

    <script src="../../../js/jquery-3.4.1.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/bootbox.min.js"></script>
    <script>
        function cerrarPagina() {
            window.close();
        }

        function detailProduct(cod_titular) {
            window.open("detalles.php?q=" + cod_titular, "_blank");
        }



        function updateProduct(cod_titular) {
            window.location = "edita_tropa.php?q=" + cod_titular;
        }
    </script>
</head>

<body>

    <h1 class="text-center" style="margin: 5px ; ">LISTAR TROPAS</h1>

    <div class="row">
        <style>
            body {
                margin: 50px 50px;
            }

            a {
                text-align: center;
            }

            body {
                margin: 10px;
            }
        </style>
    </div>

    <br>
    <table class="table table-bordered table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Tropa Numero</th>
                <th>Movil</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>CELULAR</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>

                <?php
                $sql = "SELECT * FROM completa WHERE tropa != 50 ORDER BY tropa ASC";
                $result = $con->query($sql);

                $tropas_vistas = [];

                while ($row = $result->fetch_assoc()) {
                    // Si ya mostramos esta tropa, la saltamos
                    if (in_array($row['tropa'], $tropas_vistas)) {
                        continue;
                    }

                    // Guardamos esta tropa como ya vista
                    $tropas_vistas[] = $row['tropa'];

                    echo "<tr>";
                    echo "<td>" . $row['tropa'] . "</td>";
                    echo "<td>" . $row['movil'] . "</td>";
                    echo "<td>" . $row['apellido_titu'] . "</td>";
                    echo "<td>" . $row['nombre_titu'] . "</td>";
                    echo "<td>" . $row['cel_titu'] . "</td>";



                    echo '<td><a class="btn btn-primary btn-sm" href="#" onclick="updateProduct(' . $row['id'] . ')">Actualizar</a></td>';
                    echo '<td><a class="btn btn-secondary btn-sm" href="#" onclick="detailProduct(' . $row['tropa'] . ')">Detalles</a></td>';

                    echo "</tr>";
                }
                ?>
        </tbody>
        </form>


    </table>
    &nbsp; &nbsp; &nbsp;
    <div class="text-center">
        <button onclick="cerrarPagina()" class="btn btn-danger btn-sm">CERRAR PAGINA</button>
    </div>
    <?php foot(); ?>
    <br>
</body>

</html>