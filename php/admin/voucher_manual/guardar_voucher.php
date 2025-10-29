<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();

$con->set_charset("utf8mb4");


echo "<br>" . $movil = $_POST['movil'];
echo "<br>" . $fe = $_POST['fecha'];
echo "<br>" . $viaje = $_POST['viaje_no'];
echo "<br>" . $cc = $_POST['cc'];
echo "<br>" . $reloj = $_POST['reloj'];
echo "<br>" . $peaje = $_POST['peaje'];
echo "<br>" . $equipaje = $_POST['equipaje'];
echo "<br>" . $adicional = $_POST['adicional'];
echo "<br>" . $plus = $_POST['plus'];




echo "<br>" . $fecha = date("d/m/Y", strtotime($fe)); // convierte a DD/MM/YYYY


//exit;

$sql = "INSERT INTO voucher_validado (    
    movil,
    fecha,
    viaje_no,
    cc,
    reloj,
    peaje,
    equipaje,
    adicional,
    plus
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->bind_param("ssiiiiiii", $movil, $fecha, $viaje, $cc, $reloj, $peaje, $equipaje, $adicional, $plus);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<br>Registro insertado correctamente.";
} else {
    echo "<br>Error al insertar el registro: " . $stmt->error;
}

$stmt->close();
header("../../menu_admin.php");
