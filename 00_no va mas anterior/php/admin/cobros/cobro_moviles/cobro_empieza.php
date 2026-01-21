<?php
session_start();
include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

// Validar que se haya recibido el parámetro
if (empty($_POST['movil'])) {
    die("No se recibió el número de móvil.");
}

$mov = intval($_POST['movil']); // seguridad: convertir a número entero
$movil = "A" . $mov;

// --- Verificar si el móvil existe ---
$sql_existe = "SELECT 1 FROM completa WHERE movil = ?";
$stmt = $con->prepare($sql_existe);
$stmt->bind_param("i", $mov);
$stmt->execute();
$resu = $stmt->get_result();

if ($resu->num_rows === 0) {
    echo "El registro no existe.";
    header("Location: inicio_cobros.php");
    exit;
}

// --- Verificar si tiene vouchers ---
$sql = "SELECT COUNT(*) AS total FROM voucher_validado WHERE movil = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $mov);
$stmt->execute();
$result = $stmt->get_result();
$hay_voucher = $result->fetch_assoc();
$total_vouchers = $hay_voucher ? intval($hay_voucher['total']) : 0;

// --- Traer datos de ventas y deuda ---
$sql_tiene_ventas = "SELECT venta_1, deuda_anterior FROM completa WHERE movil = ?";
$stmt = $con->prepare($sql_tiene_ventas);
$stmt->bind_param("i", $mov);
$stmt->execute();
$resu = $stmt->get_result();
$linea = $resu->fetch_assoc();

$hay_ventas = $linea ? $linea['venta_1'] : 0;
$deuda_ant  = $linea ? $linea['deuda_anterior'] : 0;

// --- Traer semanas ---
$sql_sem = "SELECT total FROM semanas WHERE movil = ?";
$stmt = $con->prepare($sql_sem);
$stmt->bind_param("i", $mov);
$stmt->execute();
$sql_res = $stmt->get_result();
$tiene_semanas = $sql_res->fetch_assoc();

$debe_semanas = $tiene_semanas ? $tiene_semanas['total'] : 0;

// --- Guardar en sesión e incluir siguiente paso ---
$_SESSION['variable'] = $movil;
include_once "cobro_siguiente.php";
