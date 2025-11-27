<?php
session_start();
include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

// --- Validar parámetro recibido ---
if (empty($_POST['movil'])) {
    die("No se recibió el número de móvil.");
}

$mov = intval($_POST['movil']);  // Sanitizado número

// --- Verificar si el móvil existe en COMPLETA ---
$sql = "SELECT venta_1, deuda_anterior FROM completa WHERE movil = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $mov);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    // Redirección segura sin echo previo
    header("Location: inicio_cobros.php?error=no_existe");
    exit;
}

$linea = $res->fetch_assoc();
$venta_1     = intval($linea['venta_1']);
$deuda_ant   = intval($linea['deuda_anterior']);


// --- Verificar cantidad de vouchers ---
$sql = "SELECT COUNT(*) AS total FROM voucher_validado WHERE movil = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $mov);
$stmt->execute();
$res = $stmt->get_result();
$total_vouchers = intval($res->fetch_assoc()['total']);


// --- Verificar deuda de semanas ---
$sql = "SELECT total FROM semanas WHERE movil = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $mov);
$stmt->execute();
$res = $stmt->get_result();
$debe_semanas = ($res->num_rows > 0) ? intval($res->fetch_assoc()['total']) : 0;


// --- Guardar en sesión ---
$_SESSION['movil']          = $mov;
$_SESSION['venta_1']        = $venta_1;
$_SESSION['deuda_anterior'] = $deuda_ant;
$_SESSION['vouchers']       = $total_vouchers;
$_SESSION['debe_semanas']   = $debe_semanas;

// Pasar al siguiente archivo
include_once "cobro_siguiente.php";
exit;
