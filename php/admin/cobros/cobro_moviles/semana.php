<?php
include_once "../../../../funciones/funciones.php";

$licencia = "licencia_desbloqueada.txt";
$archivo = "semana.txt";
$flag_ejecucion = "ejecutado_" . date("Y-m-d_H-i") . ".lock"; // bandera por minuto

$mes_actual = date("m");
$semana_actual = date('W');
$fecha = date("Y-m-d_H-i");

// ---------------------------------------------
// 1️⃣ Verificación de licencia
// ---------------------------------------------
if (file_exists($licencia)) {
    $mes_siguiente = file_get_contents($licencia);
    $otro_mes = $mes_actual + 1;
    if ($otro_mes > 12) $otro_mes = 1;

    if ($mes_siguiente == $otro_mes) {
        echo "<br>Licencia vigente hasta el 1° del próximo mes...";
    } else {
        echo "<br>Licencia mensual vencida...";
        exit;
    }
} 

// ---------------------------------------------
// 2️⃣ Prevención de doble ejecución
// ---------------------------------------------
if (file_exists($flag_ejecucion)) {
    echo "<br>⚠️ Script ya ejecutado hoy. Cancelado para evitar duplicación.";
    exit;
}

// ---------------------------------------------
// 3️⃣ Lógica principal
// ---------------------------------------------
$con = conexion();
$con->set_charset("utf8mb4");

if (file_exists($archivo)) {
    $semana_anterior = trim(file_get_contents($archivo));
    //echo "Semana archivada: $semana_anterior";

    if ($semana_actual != $semana_anterior) {

        // Bloqueo al escribir el archivo
        file_put_contents($archivo, $semana_actual, LOCK_EX);

      //  echo "<br>¡Nueva semana detectada! Actualizando...";

        $sql_3 = "SELECT * FROM semanas WHERE 1";
        $listarla = $con->query($sql_3);

        while ($verla = $listarla->fetch_assoc()) {
            $movil = $verla['movil'];
            $x_semana = (float)$verla['x_semana'];
            $total = (float)$verla['total'];
            $paga_semanas = $verla['activo'];

            if ($paga_semanas === "SI") {
                $suma = $x_semana + $total;
                $fecha = date("Y-m-d");
                $inc_semana = "UPDATE semanas SET total = '$suma', fecha = '$fecha' WHERE movil = '$movil'";
                $con->query($inc_semana);
            }
        }

        // Crear bandera para marcar ejecución
        file_put_contents($flag_ejecucion, "Ejecutado: $fecha", LOCK_EX);
        echo "<br>✅ Actualización completada correctamente.";
    } else {
        echo "<p>No hay actualizacion de semana.</p>";
    }
} else {
    // Si no existe el archivo, se crea
    file_put_contents($archivo, $semana_actual, LOCK_EX);
    echo "<br>Archivo de semana creado con valor: $semana_actual";
}
