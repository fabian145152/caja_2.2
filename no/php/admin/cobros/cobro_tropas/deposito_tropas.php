<?php

session_start();

include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");


$tropa = $_POST['tropa'];
$dep_ft = $_POST['dep_ft'];
$total_tropa = $_POST['total_trop'];

echo "<br>No de tropa: " . $tropa;
echo "<br>Deposito en FT. " . $dep_ft;

$total_tropa = abs($total_tropa);

echo "<br>Total deuda de la tropa: " . $total_tropa;

if ($total_tropa == $dep_ft) {
    if (isset($_POST['moviles'])) {
        $moviles_recibidos = $_POST['moviles']; // array de móviles

        foreach ($moviles_recibidos as $index => $movil) {
            ${"movil_" . ($index + 1)} = $movil;


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
} elseif ($total_tropa < $dep_ft) {
    echo "<br>Paga de más...";
    echo "<br>Tropa: " . $tropa;

    // Calcular saldo a favor
    $saldo_a_favor = $dep_ft - $total_tropa;
    echo "<br>Saldo a favor: " . $saldo_a_favor;

    // Obtener todos los móviles de la tropa
    $stmt = $con->prepare("SELECT movil, deuda_anterior FROM completa WHERE tropa = ?");
    $stmt->bind_param("i", $tropa);
    $stmt->execute();
    $result = $stmt->get_result();

    $primer_movil = null;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movil = $row['movil'];
            $deuda_anterior = $row['deuda_anterior'];
            echo "<br>Movil: " . $movil;

            // Guardamos el primer móvil
            if ($primer_movil === null) {
                $primer_movil = $movil;

                // 1️⃣ Actualizar saldo_a_favor_ft y poner deuda_anterior a 0 si es > 0
                if ($deuda_anterior > 0) {
                    $sql_deuda = $con->prepare("UPDATE completa SET saldo_a_favor_ft = ?, deuda_anterior = 0 WHERE movil = ?");
                    echo "<br>Deuda anterior mayor a 0, se pone a 0.";
                } else {
                    $sql_deuda = $con->prepare("UPDATE completa SET saldo_a_favor_ft = ? WHERE movil = ?");
                }
                $sql_deuda->bind_param("di", $saldo_a_favor, $primer_movil);
                $sql_deuda->execute();
                $sql_deuda->close();
            }

            // 2️⃣ Borrar todos los vouchers validados para cada móvil
            $sql_borra_vou = $con->prepare("DELETE FROM voucher_validado WHERE movil = ?");
            $sql_borra_vou->bind_param("i", $movil);
            $sql_borra_vou->execute();
            $sql_borra_vou->close();

            // 3️⃣ Actualizar semanas: total = x_semana
            $stmt_sem = $con->prepare("SELECT total, x_semana FROM semanas WHERE movil = ?");
            $stmt_sem->bind_param("i", $movil);
            $stmt_sem->execute();
            $res_sem = $stmt_sem->get_result();
            $actualiza_sem = $res_sem->fetch_assoc();
            $stmt_sem->close();

            if ($actualiza_sem) {
                $total = $actualiza_sem['x_semana'];
                $sql_semanas = $con->prepare("UPDATE semanas SET total = ? WHERE movil = ?");
                $sql_semanas->bind_param("ii", $total, $movil);
                $sql_semanas->execute();
                $sql_semanas->close();
            }
        }
    } else {
        echo "<br>No se encontraron móviles para la tropa " . $tropa;
    }

    header("Location: ../cobro_moviles/inicio_cobros.php");
    exit;
} elseif ($total_tropa > $dep_ft) {
    echo "<br>Queda con deuda...";
    echo "<br>Tropa: " . $tropa;

    // Calcular deuda
    $deuda_anterior = $total_tropa - $dep_ft;
    echo "<br>Deuda a cargar: " . $deuda_anterior;

    // Obtener todos los móviles de la tropa
    $stmt = $con->prepare("SELECT movil FROM completa WHERE tropa = ?");
    $stmt->bind_param("i", $tropa);
    $stmt->execute();
    $result = $stmt->get_result();

    $primer_movil = null;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movil = $row['movil'];
            echo "<br>Movil: " . $movil;

            // Guardamos el primer móvil
            if ($primer_movil === null) {
                $primer_movil = $movil;

                // 1️⃣ Poner saldo_a_favor_ft = 0 y cargar deuda_anterior
                $sql_deuda = $con->prepare("UPDATE completa SET saldo_a_favor_ft = 0, deuda_anterior = ? WHERE movil = ?");
                $sql_deuda->bind_param("di", $deuda_anterior, $primer_movil);
                $sql_deuda->execute();
                $sql_deuda->close();
            }

            // 2️⃣ Borrar todos los vouchers validados para cada móvil
            $sql_borra_vou = $con->prepare("DELETE FROM voucher_validado WHERE movil = ?");
            $sql_borra_vou->bind_param("i", $movil);
            $sql_borra_vou->execute();
            $sql_borra_vou->close();

            // 3️⃣ Actualizar semanas: total = x_semana
            $stmt_sem = $con->prepare("SELECT total, x_semana FROM semanas WHERE movil = ?");
            $stmt_sem->bind_param("i", $movil);
            $stmt_sem->execute();
            $res_sem = $stmt_sem->get_result();
            $actualiza_sem = $res_sem->fetch_assoc();
            $stmt_sem->close();

            if ($actualiza_sem) {
                $total = $actualiza_sem['x_semana'];
                $sql_semanas = $con->prepare("UPDATE semanas SET total = ? WHERE movil = ?");
                $sql_semanas->bind_param("ii", $total, $movil);
                $sql_semanas->execute();
                $sql_semanas->close();
            }
        }
    } else {
        echo "<br>No se encontraron móviles para la tropa " . $tropa;
    }

    header("Location: ../cobro_moviles/inicio_cobros.php");
    exit;
}
