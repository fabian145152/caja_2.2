<?php
include_once "../../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

echo "<br>Movil :" . $movil = $_GET['movil'];
echo "<br>";
echo "<br>A depositar en FT al movil $movil: " . $depositar =  $_GET['resultadoResta'];

$sql_sem = "SELECT * FROM semanas WHERE movil = '$movil'";
$res = $con->query($sql_sem);
$se_adeu = $res->fetch_assoc();

echo "<br>Paga x semana" . $paga_x_semana = $se_adeu['x_semana'];
echo "<br>Total semanas: " . $semanas_adeudadas = $se_adeu['total'];

echo "<br><br><br>";
echo "<br>Cantidad de semanas que debe: " . $debe_semanas = $semanas_adeudadas / $paga_x_semana - 1;
echo "<br>Semanas postergadas: " . $semanas = $_GET['postergar_semana'];
echo "<br>Cobra total de semanas: " . $queda_semanas = $debe_semanas - $semanas;
echo "<br>Guarda en semanas: " . $guarda_semanas = $semanas;
$guarda_semanas = $guarda_semanas + 1;
echo "<br>Importe de semanas = " . $guarda_semanas * $paga_x_semana;

echo "<br>Total: " . $total = $guarda_semanas * $paga_x_semana;

//exit;

$sql_comp = "SELECT * FROM completa WHERE movil='$movil'";
$result = $con->query($sql_comp);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<br>ID: " . $row["id"] . "<br>";
        echo "Movil: " . $row["movil"] . "<br>";
        echo "Deuda anterior: " . $row["deuda_anterior"] . "<br>";
        echo "Saldo a favor: " . $row["saldo_a_favor_ft"] . "<br>";
        echo "venta_1 " . $row["venta_1"] . "<br>";
        echo "venta_2 " . $row["venta_2"] . "<br>";
        echo "venta_3 " . $row["venta_3"] . "<br>";
        echo "venta_4 " . $row["venta_4"] . "<br>";
        echo "venta_5 " . $row["venta_5"] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No se encontraron resultados.";
}

$sql_sem = "SELECT * FROM voucher_validado WHERE movil='$movil'";

$resulta = $con->query($sql_sem);

if ($resulta->num_rows > 0) {
    while ($row = $resulta->fetch_assoc()) {
        echo "ID: " . $row["id"] . "<br>";
        echo "Movil: " . $row["movil"] . "<br>";
        echo "Reloj: " . $row["reloj"] . "<br>";
        echo "Peaje: " . $row["peaje"] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No se encontraron resultados.";
}

//exit;
$deuda_anterior = 0;
$saldo_a_favor = 0;
$venta_1 = 0;
$venta_2 = 0;
$venta_3 = 0;
$venta_4 = 0;
$venta_5 = 0;

echo "<br>Total: " . $total;

//exit;
$mensaje;

obsDeuda($con, $movil, $postergar_semana, $mensaje);
borraVoucher($con, $movil);
actualizaSemPagadas($con, $movil, $total);
actDeuAntSalaFavor($con, $movil, $deuda_anterior, $saldo_a_favor, $venta_1, $venta_2, $venta_3, $venta_4, $venta_5);
guardaCajaFinal($con, $movil, $fecha, $new_dep_ft, $saldo_ft, $saldo_voucher, $dep_voucher, $usuario, $observaciones, $diez, $noventa, $paga_de_viajes);

header("Location:inicio_cobros.php");
exit;
