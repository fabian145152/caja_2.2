<?php
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");
echo $tropa = $_GET['q'];


$sql_contar = "SELECT COUNT(*) AS total FROM completa WHERE tropa = '$tropa'";

$stmt_contar = $con->query($sql_contar);

if ($stmt_contar) {
    $row_3 = $stmt_contar->fetch_assoc();
    $cant_cargas = $row_3['total'];
} else {
    echo "Error en la consulta: " . $con->error;
}


$sql_activo = "SELECT * FROM semanas WHERE tropa = '$tropa'";
$sql_comp = "SELECT * FROM `completa` WHERE tropa = '$tropa' ORDER BY movil";
$res_comp = $con->query($sql_comp);
$sel_res_activo = $con->query($sql_activo);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TROPA COMPLETA</title>

    <script src="../../../js/jquery-3.4.1.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/bootbox.min.js"></script>
    <?php head(); ?>
    <style>
        select,
        input.texto {
            width: 120px;
        }

        * {
            font-size: 12px;
        }

        a {
            margin-right: 10px;
            /* Separación entre enlaces */
            text-decoration: none;
            /* Sin subrayado */
            color: blue;
            /* Color del texto */
            font-size: 20px;
        }

        a:hover {
            text-decoration: underline;
            /* Subrayado al pasar el mouse */
        }
    </style>
    <script>
        function detalleProduct(cod_uni_comp) {
            window.location = "det_uni_comp.php?q=" + cod_uni_comp;
        }



        function updateProduct(cod_uni_comp) {
            window.location = "edit_uni_comp.php?q=" + cod_uni_comp;
        }
    </script>
</head>

<body>
    <h2 class="text-center" style="margin: 5px ; ">LISTADO DE LA TROPA COMPLETA
        <?php echo $cant_cargas . " UNIDADES CARGADAS." ?>
        <button onclick="cerrarPagina()" class="btn btn-primary btn-sm">CERRAR PAGINA</button>
        <?php
        $tro = "SELECT * FROM completa WHERE tropa = '$tropa'";
        $lee_tit = $con->query($tro);
        $tit = $lee_tit->fetch_assoc();
        $titular = $tit['nombre_titu'];
        ?>

    </h2>
    <div>
        <tr>
            <h4>
                <th>Nombre: <?php echo htmlspecialchars($tit['nombre_titu']); ?></th>
            </h4>
            <h4>
                <th>Apellido: <?php echo htmlspecialchars($tit['apellido_titu']); ?></th>
            </h4>
            <h4>
                <th>Direccion: <?php echo htmlspecialchars($tit['direccion_titu']); ?></th>
            </h4>
            <h4>
                <th>Celular: <?php echo htmlspecialchars($tit['cel_titu']); ?></th>
            </h4>
        </tr>
    </div>

    <table class=" table table-bordered table-sm table-hover">
        <thead class="thead-dark">



            <tr>

                <th>Movil</th>
                <th>Nom ch. dia</th>
                <th>Ape ch. dia</th>
                <th>Cel ch. dia</th>
                <th>Nom ch. noche</th>
                <th>Ape ch. noche</th>
                <th>Cel noche</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Dominio</th>
                <th>año</th>
            </tr>
        </thead>
        <?php


        ?>
        <?php
        while ($row = $res_comp->fetch_assoc()) {
        ?>
            <form action="del_uni_comp.php" method="get">
                <tr>
                    <!-- <th><?php echo $id = $row['id'] ?></th> -->
                    <th><?php echo $movil = $row['movil']; ?></th>
                    <th><?php echo $row['nombre_chof_1'] ?></th>
                    <th><?php echo $row['apellido_chof_1'] ?></th>
                    <th><?php echo $row['cel_chof_1'] ?></th>
                    <th><?php echo $row['nombre_chof_2'] ?></th>
                    <th><?php echo $row['apellido_chof_2'] ?></th>
                    <th><?php echo $row['cel_chof_2'] ?></th>
                    <th><?php echo $row['marca'] ?></th>
                    <th><?php echo $row['modelo'] ?></th>
                    <th><?php echo $row['dominio'] ?></th>
                    <th><?php echo $row['año'] ?></th>

                </tr>
            </form>

        <?php
        }
        ?>
        </tr>
    </table>
    <br><br>
    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>
    <?php foot() ?>
</body>

</html>