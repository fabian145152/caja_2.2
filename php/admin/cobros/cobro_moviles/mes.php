<?php
include_once "../../../../funciones/funciones.php";

if (!file_exists("mes.txt")) {

    exit;
}



$licencia = "syst_block.txt";
$archivo = "mes.txt";


$mes_actual = date("m");



if (file_exists($archivo)) {
    $mes_guardado = trim(file_get_contents($archivo));

    $mes_siguiente = $mes_actual + 1;
    if ($mes_siguiente > 12) $mes_siguiente = 1;

    if ($mes_guardado == $mes_actual) {
        echo "
<div style='
    margin:20px;
    padding:15px;
    background:#ffecec;
    color:#b30000;
    border:1px solid #ff0000;
    font-weight:bold;
    text-align:center;
'>
⚠️ LICENCIA MENSUAL VENCIDA<br>
Debe cargar la licencia mensual para continuar.
</div>
";
        exit;

        exit;
    }

    if ($mes_guardado == $mes_siguiente) {
        $fecha = new DateTime("now");
        $fecha->modify("last day of this month");
        echo "<br>Licencia valida hasta: " . $fecha->format("d-m-Y");
    } else {
        echo "<br>Mes inválido. Acceso denegado.";
        exit;
    }
}
