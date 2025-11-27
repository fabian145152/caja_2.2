<?php
include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

if (empty($_GET['id']) || empty($_GET['movil'])) {
    die("Faltan datos para eliminar el voucher.");
}

$id = intval($_GET['id']);
$movil = intval($_GET['movil']);

// Eliminar voucher
$sql = "DELETE FROM voucher_validado WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

// Volver al panel recalculado
header("Location: nuevo_cobro.php?movil=$movil");
exit;
