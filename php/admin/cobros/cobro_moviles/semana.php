<?php

//session_start();
include_once "../../../../funciones/funciones.php";

$licencia = "licencia_desbloqueada.txt";

$mes_actual = date("m");


if (file_exists($licencia)) {
    // Si el archivo existe, leer el contenido para obtener la última semana registrada
    //echo "Mes leido del archivo: " . $mes_siguiente = file_get_contents($licencia);
    $mes_siguiente = file_get_contents($licencia);


    ##--------------------------------------------------------------------------------
    $otro_mes = $mes_actual + 1;

    // Ajustar si pasa de diciembre
    if ($otro_mes > 12) {
        $otro_mes = 1;
    }

    if ($mes_siguiente == $otro_mes) {
        echo "<br>Licencia vence el 1° de cada mes...";
    } else {
        echo "<br>Licencia vencida...";
        exit;
    }

    ##--------------------------------------------------------------------------------
}

$con = conexion();
$con->set_charset("utf8mb4");

$archivo = "semana.txt";

$semana_actual = date('W');
$mes_actual = date("m");

$fecha = date("Y-m-d");


if (file_exists($archivo)) {
    // Si el archivo existe, leer el contenido para obtener la última semana registrada
    echo "<br>Semana leida del archivo: " . $semana_anterior = file_get_contents($archivo);



    if ($semana_actual != $semana_anterior) {
        $variable = 1;
        file_put_contents($archivo, $semana_actual);

        echo "¡La semana se ha incrementado!... " . $variable;
        $sql_3 = "SELECT * FROM semanas WHERE 1";
        $listarla = $con->query($sql_3);

        while ($verla = $listarla->fetch_assoc()) {
            $movil = $verla['movil'];
            $x_semana = $verla['x_semana'];
            $total = $verla['total'];
            $paga_semanas = $verla['activo']; // Este valor viene desde la base

            if ($paga_semanas === "SI") {
                $suma = $x_semana + $total;
                $fecha = date("Y-m-d");

                $inc_semana = "UPDATE semanas SET total = '$suma', fecha = '$fecha' WHERE movil = '$movil'";
                $con->query($inc_semana);
            } else {
                $variable = file_get_contents("semana.txt");
            }
        }
    }
}
