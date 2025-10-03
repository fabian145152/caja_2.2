<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

//exit;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movil = isset($_POST['movil']) ? $_POST['movil'] : null;
    $opciones = isset($_POST['opciones']) ? $_POST['opciones'] : [];


    if ($movil && !empty($opciones)) {
        // Traer registro de la tabla completa
        $sql_comp = "SELECT * FROM completa WHERE movil = " . (int)$movil;
        $res_comp = $con->query($sql_comp);
        $row_comp = $res_comp->fetch_assoc();

        foreach ($opciones as $venta) {
            // Buscar el primer campo libre (venta_1 ... venta_5)
            for ($i = 1; $i <= 5; $i++) {
                $campo = "venta_" . $i;

                if ($row_comp[$campo] == 0) {
                    $sql_upd = "UPDATE completa SET $campo = " . (int)$venta . " WHERE movil = " . (int)$movil;
                    $con->query($sql_upd);

                    // actualizar también la copia local, así no pisamos el mismo campo en el mismo foreach
                    $row_comp[$campo] = $venta;
                    break; // salimos del for, pasamos al siguiente producto
                }
            }
        }

        echo "Guardado correctamente.";
    } else {
        echo "No seleccionaste ninguna opción.";
    }
} else {
    echo "Método de solicitud no válido.";
}

header("Location: inicio_ventas.php");
