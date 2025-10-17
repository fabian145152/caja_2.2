<?php

session_start();

include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");


$tropa = $_POST['tropa'];
echo "<br>" . $tropa;



echo "<br>";
if (isset($_POST['moviles'])) {
    $moviles_recibidos = $_POST['moviles']; // array de móviles

    foreach ($moviles_recibidos as $index => $movil) {
        // Creamos variables dinámicas $movil_1, $movil_2, etc.
        ${"movil_" . ($index + 1)} = $movil;

        //echo "Móvil " . ($index + 1) . ": " . ${"movil_" . ($index + 1)} . "<br>";

        echo "<br>" . $movil;
        ##-------------------------------------------------------------------------
        // 1️⃣ Preparar la sentencia
        $sql_borra_vou = "DELETE FROM voucher_validado WHERE movil = ?";

        if ($stmt = $con->prepare($sql_borra_vou)) {
            // 2️⃣ Vincular parámetros
            $stmt->bind_param("i", $movil); // "i" para entero, "s" si es string

            // 3️⃣ Ejecutar
            if ($stmt->execute()) {
                echo " Registros eliminados correctamente.";
            } else {
                echo "Error al eliminar registros: " . $stmt->error;
            }

            // 4️⃣ Cerrar statement
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $con->error;
        }
        ##----------------------------------------------------------------------
        $sql_act_sem = "SELECT * FROM semanas WHERE movil = '$movil' ";
        $act_se = $con->query($sql_act_sem);
        $actualiza_sem = $act_se->fetch_assoc();
        echo $total = $actualiza_sem['total'];
        echo $x_semana = $actualiza_sem['x_semana'];
        $total = $x_semana;


        // Asumiendo que ya tienes la conexión $con y las variables $total y $movil definidas

        $sql_semanas = "UPDATE semanas SET total = ? WHERE movil = ?";

        if ($stmt = $con->prepare($sql_semanas)) {
            $stmt->bind_param("ii", $total, $movil); // "i" para enteros; usar "s" si son strings

            if ($stmt->execute()) {
                echo "Registro actualizado correctamente.";
            } else {
                echo "Error al actualizar: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $con->error;
        }
    }
}

header("Location: ../cobro_moviles/inicio_cobros.php");
exit;
