<?php
session_start();
include_once "../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

$movil = $_POST['movil'] ?? null;
$nuevo_saldo = $_POST['saldo_a_favor'] ?? null;

if (!$movil || $nuevo_saldo === null) {
    die("Datos incompletos.");
}

$stmt = $con->prepare("UPDATE completa SET saldo_a_favor_ft = ? WHERE movil = ?");
$stmt->bind_param("ds", $nuevo_saldo, $movil);

if ($stmt->execute()) {
    echo "<h3>Saldo actualizado correctamente para el móvil $movil.</h3>";
    echo "<script>setTimeout(() => window.close(), 1500);</script>";
} else {
    echo "<h3>Error al actualizar el saldo: " . htmlspecialchars($stmt->error) . "</h3>";
}

$stmt->close();
$con->close();
