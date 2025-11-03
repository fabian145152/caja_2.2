<?php
session_start();
include_once "../../../../funciones/funciones.php";


$con = conexion();
$con->set_charset("utf8mb4");

// 🟢 Activar modo depuración (true para ver detalles, false para producción)
define("DEBUG", true);

// 🔒 Validar datos POST obligatorios
if (!isset($_POST['tropa'], $_POST['dep_ft'], $_POST['total_trop'])) {
    die("Error: faltan datos obligatorios.");
}

$tropa = intval($_POST['tropa']);
$dep_ft = floatval($_POST['dep_ft']);
$total_tropa = abs(floatval($_POST['total_trop']));


$diferencia = $dep_ft - $total_tropa;

if (DEBUG) {
    echo "<br>No de tropa: $tropa";
    echo "<br>Depósito en FT: $dep_ft";
    echo "<br>Total tropa (a pagar): $total_tropa";
    echo "<br>Diferencia (depósito - total): $diferencia";
}


// 🟢 Caso 1: Pago justo
if ($total_tropa == $dep_ft) {
    echo "<br>Paga justo...";

    if (isset($_POST['moviles']) && is_array($_POST['moviles'])) {
        foreach ($_POST['moviles'] as $movil) {
            $movil = intval($movil);
            echo "<br>Movil " . $movil;
            echo "<br>Tropa " . $tropa;


            limpiarMovil($con, $movil);
        }
    }



    header("Location: ../cobro_moviles/inicio_cobros.php");
    exit;
}

// 🟡 Caso 2: Pago de más → saldo a favor
elseif ($total_tropa < $dep_ft) {
    echo "<br>Paga de mas...";
    $saldo_a_favor = $dep_ft - $total_tropa;

    $stmt = $con->prepare("SELECT movil, deuda_anterior FROM completa WHERE tropa = ?");
    $stmt->bind_param("i", $tropa);
    $stmt->execute();
    $result = $stmt->get_result();

    $primer_movil = null;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movil = intval($row['movil']);
            $deuda_anterior = floatval($row['deuda_anterior']);

            if ($primer_movil === null) {
                $primer_movil = $movil;

                if ($deuda_anterior > 0) {
                    $sql_deuda = $con->prepare("UPDATE completa SET saldo_a_favor_ft = ?, deuda_anterior = 0 WHERE movil = ?");
                } else {
                    $sql_deuda = $con->prepare("UPDATE completa SET saldo_a_favor_ft = ? WHERE movil = ?");
                }

                $sql_deuda->bind_param("di", $saldo_a_favor, $primer_movil);
                $sql_deuda->execute();
                $sql_deuda->close();
            }

            limpiarMovil($con, $movil);
        }
    }

    header("Location: ../cobro_moviles/inicio_cobros.php");
    exit;
}

// 🔴 Caso 3: Pago insuficiente → queda deuda
elseif ($total_tropa > $dep_ft) {
    echo "<br>Paga de menos...";
    $deuda_anterior = $total_tropa - $dep_ft;

    $stmt = $con->prepare("SELECT movil FROM completa WHERE tropa = ?");
    $stmt->bind_param("i", $tropa);
    $stmt->execute();
    $result = $stmt->get_result();

    $primer_movil = null;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movil = intval($row['movil']);
            if ($primer_movil === null) {
                $primer_movil = $movil;

                $sql_deuda = $con->prepare("UPDATE completa SET saldo_a_favor_ft = 0, deuda_anterior = ? WHERE movil = ?");
                $sql_deuda->bind_param("di", $deuda_anterior, $primer_movil);
                $sql_deuda->execute();
                $sql_deuda->close();
            }

            limpiarMovil($con, $movil);
        }
    }
    header("Location: ../cobro_moviles/inicio_cobros.php");
    exit;
}
