<?php
session_start();
include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

// 🟢 Activar modo depuración
define("DEBUG", true);

// 🔒 Validar datos POST
if (!isset($_POST['tropa'], $_POST['dep_ft'], $_POST['total_trop'])) {
    die("Error: faltan datos obligatorios.");
}

$tropa = intval($_POST['tropa']);
$dep_ft = round(floatval($_POST['dep_ft']), 2);
$total_tropa = round(abs(floatval($_POST['total_trop'])), 2);

$diferencia = round($dep_ft - $total_tropa, 2);

if (DEBUG) {
    echo "<br>Tropa: $tropa";
    echo "<br>Deposito FT: $dep_ft";
    echo "<br>Total tropa: $total_tropa";
    echo "<br>Diferencia: $diferencia";
}

//////////////////////////////////////////////////////////////////
// 🟢 PAGO JUSTO
//////////////////////////////////////////////////////////////////

if (abs($diferencia) < 0.01) {

    if (DEBUG) {
        echo "<br>Paga justo";
    }

    if (isset($_POST['moviles']) && is_array($_POST['moviles'])) {

        foreach ($_POST['moviles'] as $movil) {

            $movil = intval($movil);

            if (DEBUG) {
                echo "<br>Movil: $movil";
            }

            // limpiar saldos
            $sql = $con->prepare("
                UPDATE completa 
                SET saldo_a_favor_ft = 0,
                    deuda_anterior = 0
                WHERE movil = ?
            ");

            $sql->bind_param("i", $movil);
            $sql->execute();
            $sql->close();

            limpiarMovil($con, $movil);
        }
    }
}

//////////////////////////////////////////////////////////////////
// 🟡 PAGA DE MAS
//////////////////////////////////////////////////////////////////

elseif ($diferencia > 0) {

    if (DEBUG) {
        echo "<br>Paga de mas";
    }

    $saldo_a_favor = $diferencia;

    $stmt = $con->prepare("
        SELECT movil, deuda_anterior 
        FROM completa 
        WHERE tropa = ?
    ");

    $stmt->bind_param("i", $tropa);
    $stmt->execute();
    $result = $stmt->get_result();

    $primer_movil = null;

    while ($row = $result->fetch_assoc()) {

        $movil = intval($row['movil']);
        $deuda_anterior = floatval($row['deuda_anterior']);

        if ($primer_movil === null) {

            $primer_movil = $movil;

            if ($deuda_anterior > 0) {

                $sql = $con->prepare("
                    UPDATE completa
                    SET saldo_a_favor_ft = ?,
                        deuda_anterior = 0
                    WHERE movil = ?
                ");
            } else {

                $sql = $con->prepare("
                    UPDATE completa
                    SET saldo_a_favor_ft = ?
                    WHERE movil = ?
                ");
            }

            $sql->bind_param("di", $saldo_a_favor, $primer_movil);
            $sql->execute();
            $sql->close();
        }

        limpiarMovil($con, $movil);
    }
}

//////////////////////////////////////////////////////////////////
// 🔴 PAGA DE MENOS
//////////////////////////////////////////////////////////////////

else {

    if (DEBUG) {
        echo "<br>Paga de menos";
    }

    $deuda_anterior = abs($diferencia);

    $stmt = $con->prepare("
        SELECT movil 
        FROM completa 
        WHERE tropa = ?
    ");

    $stmt->bind_param("i", $tropa);
    $stmt->execute();
    $result = $stmt->get_result();

    $primer_movil = null;

    while ($row = $result->fetch_assoc()) {

        $movil = intval($row['movil']);

        if ($primer_movil === null) {

            $primer_movil = $movil;

            $sql = $con->prepare("
                UPDATE completa
                SET saldo_a_favor_ft = 0,
                    deuda_anterior = ?
                WHERE movil = ?
            ");

            $sql->bind_param("di", $deuda_anterior, $primer_movil);
            $sql->execute();
            $sql->close();
        }

        limpiarMovil($con, $movil);
    }
}

//////////////////////////////////////////////////////////////////

header("Location: ../cobro_moviles/inicio_cobros.php");
exit;
