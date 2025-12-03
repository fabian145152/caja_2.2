<?php

//include_once "../../../../funciones/funciones.php";
//$con = conexion();
//$con->set_charset("utf8mb4");
$deuda_ant = 0;
$tot_voucher = 0;
$total_ventas = 0;
$deuda_anterior = 0;
$deuda_ant = 0;
$venta_1 = 0;
$venta_2 = 0;
$venta_3 = 0;
$venta_4 = 0;
$venta_5 = 0;
$cobra = 0;
$dep_para_movil = 0;
$cobra_2 = 0;
$adu = 0;

if (isset($_GET['movil'])) {
    $movil = $_GET['movil'];
    htmlspecialchars($movil, ENT_QUOTES, 'UTF-8');
} else {
    $movil = $_POST['movil'];
}

//Veridica si existe movil
$sql_comp = "SELECT * FROM completa WHERE movil = $movil";
$res_comp = $con->query($sql_comp);
$row_comp = $res_comp->fetch_assoc();
$row_comp['movil'];
$saldo_a_favor = $row_comp['saldo_a_favor_ft'];
$viajes_que_no_se_cobraron = $row_comp['v_sem_siguiente'];
$deu_ant = $row_comp['deuda_anterior'];
$bonif = $row_comp['observaciones_deuda'];

if ($row_comp['movil'] == 0) {
    echo "El movil no existe...";
    exit;
}

$amovil = "A" . $movil;

$sql_con_voucher = "SELECT COUNT(*) AS total_registros FROM voucher_validado WHERE movil = '$movil'";
$result = $con->query($sql_con_voucher);

if ($result->num_rows > 0) {
    // Obtener el resultado
    $row = $result->fetch_assoc();
    $can_viajes = $row['total_registros'];
}

$total = 0;
$ven_1 = 0;
$ven_2 = 0;
$ven_3 = 0;
$ven_4 = 0;
$ven_5 = 0;

$saldo_a_favor = $row_comp['saldo_a_favor_ft'];
$nombre_titu = $row_comp['nombre_titu'];
$apellido_titu = $row_comp['apellido_titu'];
$nombre_chof = $row_comp['nombre_chof_1'];
$apellido_chof_1 = $row_comp['apellido_chof_1'];
$semana = $row_comp['x_semana'];
$imp_viaje = $row_comp['x_viaje'];
$deuda_anterior = $row_comp['deuda_anterior'];

$venta_1 = $row_comp['venta_1'];
$venta_2 = $row_comp['venta_2'];
$venta_3 = $row_comp['venta_3'];
$venta_4 = $row_comp['venta_4'];
$venta_5 = $row_comp['venta_5'];

if ($venta_2 != 0) {
    $sql_venta_2 = "SELECT * FROM productos WHERE id = $venta_2";
    $res_venta_2 = $con->query($sql_venta_2);
    $row_venta_2 = $res_venta_2->fetch_assoc();
}

if ($venta_3 != 0) {
    $sql_venta_3 = "SELECT * FROM productos WHERE id = $venta_3";
    $res_venta_3 = $con->query($sql_venta_3);
    $row_venta_3 = $res_venta_3->fetch_assoc();
}

if ($venta_4 != 0) {
    $sql_venta_4 = "SELECT * FROM productos WHERE id = $venta_4";
    $res_venta_4 = $con->query($sql_venta_4);
    $row_venta_4 = $res_venta_4->fetch_assoc();
}
if ($venta_5 != 0) {
    $sql_venta_5 = "SELECT * FROM productos WHERE id = $venta_5";
    $res_venta_5 = $con->query($sql_venta_5);
    $row_venta_5 = $res_venta_5->fetch_assoc();
}
if ($venta_1 != 0) {
    $sql_venta_1 = "SELECT * FROM productos WHERE id = $venta_1";
    $res_venta_1 = $con->query($sql_venta_1);
    $row_venta_1 = $res_venta_1->fetch_assoc();
}

## Es lo que paga por semana
$sql_semana = "SELECT * FROM abono_semanal WHERE id = $semana";
$sql_semana = $con->query($sql_semana);
$row_semana = $sql_semana->fetch_assoc();

$abona_x_semana = $row_semana['abono'] . " ";
$debe_de_semana = $row_semana['importe'];

## Es lo que paga por viaje
$sql_viaje = "SELECT * FROM abono_viaje WHERE id = $imp_viaje";
$sql_viaje = $con->query($sql_viaje);
$row_viaje = $sql_viaje->fetch_assoc();

$nom_viaje = $row_viaje['abono'] . " ";
$paga_x_viaje = $row_viaje['importe'];

## Es lo que debe de semanas
$sql_debe_semanas = "SELECT * FROM semanas WHERE movil = $movil";
$sql_debe_semanas = $con->query($sql_debe_semanas);
$row_debe_semanas = $sql_debe_semanas->fetch_assoc();
$deuda_semanas_anteriores = $row_debe_semanas['total'];
$row_debe_semanas['x_semana'];

$fecha = $row_debe_semanas['fecha'];

$numero_semana_ddbb = date("W", strtotime($fecha));

##variables de pago semanal e importe de semanas adeudadas
$paga_x_semana = $row_debe_semanas['x_semana'];
$debe_de_semanas = $row_debe_semanas['total'];

## Voucher validads
$sql_voucher = "SELECT * FROM voucher_validado WHERE movil = '$movil' ORDER BY viaje_no";
$sql_voucher = $con->query($sql_voucher);

?>
<!DOCTYPE html>
<html lang="en-es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VISTA COBRO</title>
    <?php head() ?>
    <link rel="stylesheet" href="../../../css/vista_con_voucher.css">
    <link rel="stylesheet" href="css_cobro_moviles/cobro_moviles.css">
    <link rel="stylesheet" href="vista_cobro.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function eliminarVoucher(id) {
            if (!confirm("¿Seguro que desea borrar este voucher?")) return;

            fetch("borra_voucher.php?q=" + id)
                .then(() => {
                    // Refresca SOLO la página actual sin abrir ventanas
                    location.reload();
                })
                .catch(err => console.error("Error al borrar:", err));
        }

        function cargarVouchers() {
            fetch("lista_vouchers.php?movil=<?= $movil ?>")
                .then(r => r.text())
                .then(html => {
                    document.getElementById("tabla_vouchers").innerHTML = html;
                });
        }



        // Selecciona el enlace por su ID
        var enlace = document.getElementById('miEnlace');

        // Añade un evento de clic al enlace
        enlace.addEventListener('click', function(event) {
            // Evita el comportamiento predeterminado del enlace (navegación)
            event.preventDefault();

            // Muestra un mensaje de alerta
            alert('¡Va a borrar todos los vouher!.....');
        });

        function cerrarPagina() {
            window.close();
        }
    </script>
</head>

<body>
    <div class="zoom-vertical">
        <ul style="border: 2px solid black; padding: 10px; border-radius: 10px; list-style-type: none;">
            <div id="contaaaenedor">
                <h4>Estado de cuenta del <strong>MOVIL: </strong> <?php echo $movil . "." ?></h4>
                <h5>Fecha:
                    <?php
                    echo date("d/m/Y");
                    ?>
                    Se le esta cobrando la semana <?php echo $semana = date('W') - 1 ?>
                </h5>
                <div class="containeraa">
                    <div class="column left-column">
                        <?php
                        if ($apellido_titu === $apellido_chof_1) {
                            echo "<strong>TITULAR: </strong>" . $nombre_titu . " " . $apellido_titu;
                        } else {
                        ?>
                            <h6> <?php echo "<strong>TITULAR: </strong>" . $nombre_titu . " " . $apellido_titu ?>&nbsp;<br>
                                <?php echo "<strong>CHOFER: </strong>" . $nombre_chof . " " . $apellido_chof_1 ?></h6>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="column left-column">
                        <?php
                        $observ = $row_comp['obs'];
                        echo '';
                        echo "<strong>COMENTARIOS: </strong>" . $observ;
                        ?>
                        <a href="../../observaciones/ver_obs.php?movil=<?php echo $movil ?>" class="btn btn-success" target="_blank">EDITAR</a>
                    </div>
                </div>
            </div>
        </ul>
        <?php
        if ($can_viajes > 0) {
        ?>
            <div id="tabla_vouchers">
                <table class="table table-bordered table-sm table-hover" style="width:50%; margin: 0 auto; zoom:80%; ">
                    <thead>
                        <tr>
                            <th class="col-sm-2">CC</th>
                            <th class="col-sm-2">Fecha</th>
                            <th class="col-sm-2">Numero</th>
                            <th class="col-sm-2">Importe</th>
                        </tr>
                    </thead>
                    <?php
                }
                $viajes_de_esta_semana = 0;
                while ($row_voucher = $sql_voucher->fetch_assoc()) {
                    $id = $row_voucher['id'];
                    if ($row_voucher['cc'] >= 0) {
                    ?>
                        <tbody>
                            <tr>
                                <!-- <th class="col-sm-2"><?php echo $id ?></th> -->
                                <th class="col-sm-2"><?php echo $cc = $row_voucher['cc'] ?></th>
                                <?php
                                $fecha_original = $row_voucher['fecha'];
                                // Crear un objeto DateTime desde la fecha original
                                $date = DateTime::createFromFormat('j/n/Y', $fecha_original);
                                // Formatear la fecha en "dd-mm-yyyy"
                                $fecha_voucher = $date->format('d-m-Y');
                                // Convertir la fecha a timestamp
                                $timestamp = strtotime($fecha_voucher);
                                // Obtener el número de semana
                                $numeroSemana = date("W", $timestamp);
                                //echo "El número de semana es: " . $numeroSemana;
                                ?>
                                <th class="col-sm-2"><?php echo $fecha_voucher ?></th>
                                <?php
                                $se_ac = date('W');   //numero de semana actual

                                if ($numeroSemana != $se_ac) {
                                    $numeroSemana;
                                } else {
                                ?>
                                <?php
                                    $viajes_de_esta_semana++;
                                }
                                ?>
                                <th class="col-sm-2"><?php echo $viaje_no = $row_voucher['viaje_no'] ?></th>
                                <?php $reloj = $row_voucher['reloj'] ?>
                                <?php $peaje = $row_voucher['peaje'] ?>
                                <?php $plus = $row_voucher['plus'] ?>
                                <?php $adicional = $row_voucher['adicional'] ?>
                                <?php $equipaje = $row_voucher['equipaje'];
                                $tot_voucher = $reloj + $peaje + $plus + $adicional + $equipaje;
                                $total += $tot_voucher;
                                ?>
                                <th class="col-sm-12"><?php echo $tot_voucher ?></th>
                                <th>
                                    <a class="btn btn-danger btn-sm"
                                        style="width:150px;"
                                        href="#"
                                        onclick="eliminarVoucher(<?php echo $row_voucher['id'] ?>)">
                                        BORRAR
                                    </a>
                                </th>
                            </tr>
                    <?php
                    }
                }
                    ?>
                        </tbody>
            </div>
            </table>
    </div>
    <?php
    $viajes_de_la_semana_anterior = $can_viajes - $viajes_de_esta_semana;
    if ($tot_voucher > 0) {
    ?>
        <div class="contenedor">

            <div class="recuadro">
                Voucher depositados: <?php echo "<strong>" . $viajes_de_la_semana_anterior . "</strong>" ?>
            </div>

            <div class="recuadro">
                Total de voucher: <?php echo "<strong>" . "$" . $total . "-" . "</strong>" ?>
            </div>
            <div class="recuadro">
                <?php
                $total_descu = $total * .9;
                ?>
                Descuentos: <?php echo "<strong>" . "$" . $total_descu . "-" . "</strong>" ?>
            </div>
        </div>
    <?php
    }
    ?>

    <?php
    if ($venta_2 != 0) {
    ?>
        <h5>Compró: <?php echo $row_venta_2['nombre'] . " " . "a" . " " . "$" . $ven_2 = $row_venta_2['precio'] ?>-</h5>
    <?php
    }
    if ($venta_3 != 0) {
    ?>
        <h5>Compró: <?php echo $row_venta_3['nombre'] . " " . "a" . " " . "$" . $ven_3 = $row_venta_3['precio'] ?>-</h5>
    <?php
    }
    if ($venta_4 != 0) {
    ?>
        <h5>Compró: <?php echo $row_venta_4['nombre'] . " " . "a" . " " . "$" . $ven_4 = $row_venta_4['precio'] ?>-</h5>
    <?php
    }

    if ($venta_5 != 0) {
    ?>
        <h5>Compró: <?php echo $row_venta_5['nombre'] . " " . "a" . " " . "$" . $ven_5 = $row_venta_5['precio'] ?>-</h5>
    <?php
    }
    if ($venta_1 != 0) {
    ?>

        <h5>Compró: <?php echo $row_venta_1['nombre'] . " " . "a" . " " . "$" . $ven_1 = $row_venta_1['precio'] ?>-</h5>
        <?php
        $total_ventas = $ven_1 + $ven_2 + $ven_3 + $ven_4 + $ven_5;
        ?>
    <?php
    }
    ?>



    <!-- <form action="cobro_fin.php" method="post" id="formulario" target="_blank"> -->
    <form action="cobro_fin.php" method="post" id="formulario" name="formulario">

        <input type="hidden" id="movil" name="movil" value="<?php echo $movil ?>">

        <div class="container">

            <div class="form-group">

                <ul style="border: 2px solid black; padding: 40px; border-radius: 10px; list-style-type: none;">

                    <!--   <h5>-------------------------------------------------------------------------</h5>  -->
                    <h2>ESTADO DE CUENTA</h2>

                    <?php
                    $abo_sem = $row_semana['importe'];
                    $cant_sem = $deuda_semanas_anteriores / $abo_sem;
                    $cobra_semana_anterior = $deuda_semanas_anteriores;
                    //$cobra_semana_anterior = $deuda_semanas_anteriores - $paga_x_semana;
                    $deudas_sumadas = $deuda_ant + $cobra_semana_anterior;
                    $debe_deuda = $total_ventas + $cobra_semana_anterior + $deuda_anterior - $saldo_a_favor;

                    ?>
                    <li>
                        <label class="mi-label">Debe <?php echo $cant_sem - 1 ?> semanas.</label>
                        <input type="hidden" id="cant_sem" name="cant_sem" value="<?php echo $cant_sem - 1 ?>">
                    </li>
                    <li>
                        <?php
                        $sql_p =  "SELECT * FROM semanas WHERE movil = $movil";
                        $res_p = $con->query($sql_p);
                        $row_p = $res_p->fetch_assoc();
                        $paga_semanas = $row_p['activo'];
                        ?>

                        <?php
                        if ($paga_semanas == "NO") {
                        ?>
                            <label class="mi-label">NO abona semanas... </label>
                        <?php
                        } else {
                        ?>
                    <li>
                        <label class="mi-label">Paga por semana</label>
                        <input type="text" id="paga_x_semana" name="paga_x_semana" style="text-align: center; font-size: 18px; font-weight: bold;"

                            value="<?php echo $paga_x_semana ?> " readonly>
                    </li>
                <?php
                        }

                        if ($paga_x_viaje == 0) {
                            echo "<h5>No paga viajes</h5>";
                        } else {
                ?>
                    <li>
                        <label class="mi-label">Paga por viaje</label>
                        <input type="text" id="paga_x_semana" name="paga_x_viaje" style="text-align: center; font-size: 18px; font-weight: bold;"
                            value="<?php echo $paga_x_viaje ?>" readonly>
                    </li>
                <?php

                        }
                        echo "<br>";

                        if ($cobra_semana_anterior == $paga_x_semana) {
                            echo "<h4><strong>ESTA AL DIA...</strong></h4>";
                        } else {
                            $adu = $cobra_semana_anterior - $paga_x_semana;
                ?>
                    <label class="mi-label">Debe semanas</label>
                    <input type="text" id="debe_sem_ant" name="debe_sem_ant" value="<?php echo $adu ?>" style='text-align: center; font-size: 18px; font-weight: bold;' readonly>
                <?php
                        }
                ?>

                </li>


                <li>
                    <?php
                    if ($total_ventas > 0) {
                        echo "<label class='mi-label'>Total de ventas</label>";
                        echo "<input type='text' style='text-align: center; font-size: 18px; font-weight: bold;' id='total_ventas' name='total_ventas' value='$total_ventas' readonly  >";
                    }
                    ?>
                </li>

                <li>
                    <!-- <label class="mi-label">Paga x viajes:</label> -->
                    <input type="hidden" id="viajes" name="viajes"
                        value="<?php echo $viajes_de_la_semana_anterior * $paga_x_viaje ?>" readonly>
                </li>
                <?php
                if ($saldo_a_favor > 0) {
                ?>
                    <label class="mi-label">TIENE SALDO A FAVOR:</label>
                    <input type="text" id="saldo_a_favor" name="saldo_a_favor" value="<?php echo $saldo_a_favor ?>"
                        style="background-color: yellow; text-align: center; font-size: 18px; font-weight: bold;" readonly>

                <?php
                }
                if ($saldo_a_favor == 0 && $deu_ant == 0 && $cobra_semana_anterior == 0 && $total_ventas == 0) {
                ?>
                    <label class="mi-label">Al dia...:</label>
                    <input type="text" id="depo_mov" name="depo_mov" value="Al dia..." style="background-color:  aqua;"
                        readonly>
                    <?php
                }
                if ($deu_ant > 0 || $cant_sem > 1 || $total_ventas > 1) {

                    $cobra_1 = $deu_ant + $cobra_semana_anterior - $saldo_a_favor + $total_ventas; // antes esta liea era

                    $cobra_2 = $cobra_1 - $abo_sem;

                    $cobra_1 = abs($cobra_1);
                    $cobra_2; // = abs($cobra_2);
                    //Cobra 2 es la linea que se muestra
                    //Cobra 1 es la que se cobra
                    //$cobra_1 = $saldo_a_favor - $cobra_semana_anterior - $deu_ant - $total_ventas;
                    //exit;


                    if ($deuda_anterior > 0) {
                    ?>
                        <label class="mi-label">Deuda anterior:</label>
                        <input type="text" id="deuda_ant" name="deuda_ant" value="<?php echo $deu_ant ?>"
                            style="background-color: orange; color: black; text-align: center; font-size: 18px; font-weight: bold;" readonly>



                    <?php
                    }

                    ?>
                    <br>

                    <?php
                    if ($cobra_2 < 0) {

                    ?>
                        <label class="mi-label">QUEDA A FAVOR</label>
                        <input type="hidden" id="depo_mov" name="depo_mov" value="<?php echo $cobra_1 ?>" readonly>
                        <input type="text" id="" name="" value="<?php echo abs($cobra_2) ?>"
                            style="background-color: green; color: yellow;" readonly>
                        <ul style="border: 2px solid black; padding: 5px; border-radius: 10px; list-style-type: none; text-align: center; font-size: 18px; font-weight: bold;">
                        <?php
                    } elseif ($cobra_2 > 0) {
                        ?>
                            <label class="mi-label">DEUDA</label>
                            <input type="hidden" id="depo_mov" name="depo_mov" value="<?php echo $cobra_1 ?>" readonly>
                            <input type="text" id="" name="" value="<?php echo $cobra_2 ?>"
                                style="background-color: red; color: yellow; text-align: center; font-size: 18px; font-weight: bold;" readonly>
                        <?php
                    } elseif ($cobra_2 == 0) {
                        ?>
                            <label class="mi-label">AL DIA</label>
                            <input type="hidden" id="depo_mov" name="depo_mov" value="<?php echo $cobra_1 ?>" readonly>
                            <input type="text" id="" name="" value="<?php echo $cobra_2 ?>"
                                style="background-color: yellow; color: yellow;" readonly>
                            <ul style="border: 2px solid black; padding: 5px; border-radius: 10px; list-style-type: none;">
                        <?php
                    }
                }
                $total_de_viajes_que_se_cobran = $viajes_de_la_semana_anterior + $viajes_de_esta_semana;
                        ?>
                        <input type="hidden" name="to_vou" id="to_vou" value="<?php echo $total ?>" readonly>
                        <?php
                        if ($viajes_de_la_semana_anterior > 0) {
                        ?>
                            <!--
                                        <li>
                                            <label for="viajes_nuevos">Depositó: </label>
                                            <input class="put" type="text" id="viajes_nuevos" name="viajes_nuevos"
                                                value="<?php echo $total_de_viajes_que_se_cobran ?>" readonly> Voucher.
                                        </li>

                                        <li>
                                            <input type="hidden" id="viajes_de_esta_semana" name="viajes_de_esta_semana"
                                                value="<?php echo
                                                        $viajes_de_esta_semana ?>">
                                        </li>
                                        <li>
                                            <label for="viajes_nuevos">Paga x viaje: </label>
                                            <input type="text" id="paga_x_viaje" name="paga_x_viaje"
                                                value="<?php echo $paga_x_viaje ?>">
                                        </li>
                                    -->
                            <li>
                                <input type="hidden" id="tot_via" name="tot_via"
                                    value="<?php echo $total_de_viajes_que_se_cobran ?>">
                            </li>


                        <?php
                        }
                        if ($viajes_que_no_se_cobraron != 0) {
                        ?>
                            <li class="resaltado">
                                <label for="viajes_anteriores">Cobrarle</label>
                                <input class="put" type="text" id="viajes_anteriores" name="viajes_anteriores"
                                    value="<?php echo $viajes_que_no_se_cobraron ?>" readonly> viajes no cobrados
                                anteriormente.
                            </li>

                        <?php
                        }
                        ?>
                            </ul>
            </div>
            <div>

                <ul style="border: 2px solid black; padding: 5px; border-radius: 10px; list-style-type: none;">
                    <h2>COBRO</h2>
                    <input type="hidden" id="can_viajes" name="can_viajes" value="<?php echo $viajes_de_esta_semana ?>">
                    <li>
                        <!-- <label class="mi-label">Debe sumado</label> -->
                        <input type="hidden" id="debe_sumado" name="debe_sumado" value="<?php echo $debe_deuda ?>"
                            readonly>
                    </li>
                    <li>
                        <!-- <label class="mi-label">RECAUDADO EN VOUCHER </label> -->
                        <input type="hidden" id="tot_voucher" name="tot_voucher" value="<?php echo $total ?>" readonly>
                    </li>
                    <li>
                        <!-- <label class="mi-label">10% descuento de vouchers</label> <!--  despues ocultarlo  -->
                        <input type="hidden" id="comi" name="comi" value="<?php echo $diez = $total * .1 ?>" readonly>
                    </li>
                    <li>
                        <!-- <label class="mi-label">90%</label> -->
                        <input type="hidden" id="comiaaa" name="comiaaa" value="<?php echo $noventa = $total * .9;
                                                                                $nov = $noventa + $deu_ant ?>"
                            readonly>
                    </li>

                    <li>
                        <?php

                        $para_movil = $debe_deuda - $noventa;
                        $descuenta_cant_de_viajes = $viajes_de_la_semana_anterior * $paga_x_viaje;
                        $pesos_viajes = $total_de_viajes_que_se_cobran * $paga_x_viaje;
                        ?>
                        <input type="hidden" id="paga_x_viaje" name="paga_x_viaje" value="<?php echo $paga_x_viaje ?>">
                        <label class="mi-label">Dep en Voucher:</label>

                        <input type="hidden" id="saldo_a_favor" name="saldo_a_favor"
                            value="<?php echo $saldo_a_favor ?>">

                        <input type="hidden" id="imp_modif" name="imp_modif" value="<?php echo $noventa ?>">
                        <input type="hidden" id="deuda_ant" name="deuda_ant" value="<?php echo $deu_ant ?>">
                        <input type="hidden" id="venta_1" name="venta_1" value="<?php echo $ven_1 ?>">
                        <input type="hidden" id="venta_2" name="venta_2" value="<?php echo $ven_2 ?>">
                        <input type="hidden" id="venta_3" name="venta_3" value="<?php echo $ven_3 ?>">
                        <input type="hidden" id="venta_4" name="venta_4" value="<?php echo $ven_4 ?>">
                        <input type="hidden" id="venta_5" name="venta_5" value="<?php echo $ven_5 ?>">

                        <?php

                        $dato_a_env = $noventa - $adu - $total_ventas - $deu_ant;
                        $depot = $adu + $total_ventas + $deu_ant + $pesos_viajes;
                        $saldo_recuento = $depot - $saldo_a_favor; // - $noventa;
                        $cuenta = $noventa - $depot;

                        if ($saldo_recuento < 0) {

                            if ($dep_para_movil < 0) {
                                echo "Debe abonar: " . $dep_para_movil = $cuenta;
                                echo "<br>";
                            }
                        } else if ($saldo_recuento == 0) {
                            echo "<br>No debe abonar nada. Está al dia: " . $saldo_recuento;
                            echo "<br>";
                        } else if ($saldo_recuento > 0) {
                            $dep = $saldo_recuento + $saldo_a_favor;
                            echo "<br>";
                        }

                        if ($para_movil < 0) {
                            $dep_para_movil = $para_movil;
                        } else {
                        ?>

                            <?php
                            $debe_abonar = $para_movil + $deu_ant;
                            ?>
                            <input type="hidden" id="debe_abonar" name="debe_abonar" value="<?php echo $debe_abonar ?>">


                            <?php
                            $total_para_base = $cobra_semana_anterior + $total_ventas + $deu_ant - $saldo_a_favor;
                            if ($viajes_que_no_se_cobraron >= 1) {
                                echo "<br>";
                                echo "Se le suman " . $viajes_que_no_se_cobraron . " De la semana anterior. ";
                                echo "<br>";
                                $cobra = $viajes_que_no_se_cobraron * $paga_x_viaje + $cobra;
                            }

                            $a_cobrar = $deuda_semanas_anteriores - $paga_x_semana + $deuda_anterior;

                            ?>
                            <br>
                            <input type="hidden" id="paga_mov" name="paga_mov" value="<?php echo $saldo_recuento ?>"
                                readonly>
                            <label class="mi-label">Debe abonar: </label>
                            <input type="text" id="deuda_semanas_anteriores" name="deuda_semanas_anteriores"
                                value="<?php echo $cobra_2 ?>" style='text-align: center; font-size: 22px; font-weight: bold; background-color: yellow;' readonly>


                    </li>
                    <br>
                <?php
                        }
                        if ($can_viajes > 0) {
                            $voucher = 1;
                ?>
                    <div class="recuadro" id="ing_via">
                        <?php
                            //include "calcula_viajes.php";

                            ##---------------------------------------------------------------------------------------------------
                            $paga_x_semana = round(isset($paga_x_semana) ? $paga_x_semana : 0);
                            $imp_viaje     = round(isset($paga_x_viaje) ? $paga_x_viaje : 0);
                            $imp_voucher   = round(isset($dato_a_env) ? $dato_a_env : 0);
                            $saldoAfavor   = isset($saldo_a_favor) ? $saldo_a_favor : 0;
                            $movil         = isset($movil) ? $movil : '';

                        ?>

                        <div class="form-container">
                            <form id="calculoForm">
                                <div class="form-group">
                                    <label>Viajes a cobrar:</label>
                                    <input type="text" id="cant_viajes" oninput="calcular()"
                                        autofocus style="text-align:center;font-size:18px;font-weight:bold;background:yellow;" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Semanas postergadas:</label>
                                    <input type="text" id="postergar_semana" value="0" oninput="calcular()"
                                        style="text-align:center;font-size:18px;font-weight:bold;background:orange;" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Resultado:</label>
                                    <input type="text" id="resultado" readonly
                                        style="text-align:center;font-size:18px;font-weight:bold;">
                                </div>

                                <p id="mensaje" style="font-weight:bold;"></p>
                            </form>
                        </div>

                        <script>
                            // Variables PHP → JS
                            const imp_viaje = <?= $imp_viaje ?>;
                            const imp_voucher = <?= $imp_voucher ?>;
                            const saldoAfavor = <?= $saldoAfavor ?>;
                            const paga_x_semana = <?= $paga_x_semana ?>;
                            const movil = "<?= $movil ?>";

                            function calcular() {
                                const cantViajes = parseFloat(document.getElementById('cant_viajes').value) || 0;
                                const semanas = parseFloat(document.getElementById('postergar_semana').value) || 0;
                                const resultado = document.getElementById('resultado');
                                const mensaje = document.getElementById('mensaje');

                                // Cálculo limpio
                                const total = imp_voucher -
                                    (cantViajes * imp_viaje) +
                                    saldoAfavor +
                                    (semanas * paga_x_semana);

                                resultado.value = total.toFixed(2);

                                if (total < 0) {
                                    resultado.style.background = "red";
                                    mensaje.textContent = "Debe abonar";
                                    mensaje.style.color = "red";
                                } else {
                                    resultado.style.background = "lightgreen";
                                    mensaje.textContent = "Habrá que depositarle";
                                    mensaje.style.color = "green";
                                }

                                // Guardar valores para la página anterior
                                localStorage.setItem("resultadoResta", total.toFixed(2));
                                localStorage.setItem("postergar_semana", semanas);
                                localStorage.setItem("movil", movil);
                            }

                            window.addEventListener('load', calcular);
                        </script>
                        <?php
                            ##---------------------------------------------------------------------------------------------------

                        ?>
                    </div>
                <?php
                        }
                ?>
                <li>
                    <br>

                    <label class="mi-label" style='text-align: center; font-size: 22px; font-weight: bold;'>Deposito FT:</label>
                    <input type="text" id="dep_ft" name="dep_ft"
                        oninput="calcularDeposito()"
                        style='text-align: center; font-size: 22px; font-weight: bold;'
                        placeholder="Ingrese dinero" autofocus autocomplete="off">

                    <?php
                    $sem = $cant_sem - 1;
                    if ($sem > 0 && $noventa > 0) {
                    ?>
                        <style>
                            input#postergar_semana {
                                width: 50px;
                                /* o el tamaño que prefieras */
                                text-align: center;
                            }
                        </style>
                        <label class="mi-label"></label>
                        <!--  
                                  <button type="submit" maxlength="3" size="3" formaction="posterga_semana.php?movil=<?php echo $movil ?> & postergar_semana=<?php echo $postergar_semana ?>" class="btn btn-dark" target="_blank">POST SEMANAS</button>
                                <input type="text" id="postergar_semana" name="postergar_semana" placeholder="N° de semanas..." value="0">
                                -->
                    <?php
                    }
                    ?>
                </li>

            </div>

            <div class="d-flex flex-column gap-2">
                <a href="inicio_cobros.php" class="btn btn-info">VOLVER</a>
                <br>
                <a href="../../editar_deudas/inicio_edit_deuda.php?movil= <?php echo $movil ?>" class="btn btn-secondary" target="_blank">
                    <?php if ($bonif !== "") {
                        echo "YA SE POSTERGARON SEMANAS";
                    } else {
                        echo "";
                    }
                    ?></a>

                <br>
                <a href="../../vauchin/exportar_tabla.php?q=<?php echo $movil ?>" class="btn btn-light" target="_blank">VAUCHIN</a>
                <br>
                <a href="../../bonifica_deuda/ver_deuda.php?movil= <?php echo $movil ?>" class="btn btn-primary" target="_blank">BONIFICA DEUDA</a>
                <br>

                <br>
                <button type="submit"
                    formaction="cobro_fin.php"
                    class="btn btn-danger"
                    onclick="return confirm('¿Estás seguro de que quieres cobrar?');">
                    COBRAR
                </button>
                <br>

                <br>

            </div>
        </div>
    </form>

    <script>
        document.getElementById('inputResultado').value = localStorage.getItem('resultadoResta') || '';
        document.getElementById('inputPostergar').value = localStorage.getItem('postergar_semana') || '';
        document.getElementById('inputMovil').value = localStorage.getItem('movil') || '';
    </script>

    <form action="depositar.php" method="get">
        <input type="hidden" id="movil" name="movil" value="<?= $movil ?>">
        <input type="hidden" id="postergar_semana_input" name="postergar_semana">
        <input type="hidden" id="resultadoResta_input" name="resultadoResta">

        <button type="submit" class="btn btn-success">Depositar</button>
    </form>

    <script>
        const form = document.querySelector('form[action="depositar.php"]');
        form.addEventListener('submit', function(e) {
            document.getElementById('postergar_semana_input').value = document.getElementById('postergar_semana').value;
            document.getElementById('resultadoResta_input').value = document.getElementById('resultadoResta').value;
        });
    </script>

    <br><br><br>
    <br><br><br>

    </div>
    <?php foot() ?>

</body>

</html>