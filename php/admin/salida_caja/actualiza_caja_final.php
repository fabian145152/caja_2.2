<?php
session_start();
include_once "../../../funciones/funciones.php";

if ($_SESSION['logueado']) {
    echo '<h4>' . "BIENVENIDO "  . $_SESSION['uname'] . '</h4>';
    $_SESSION['time'] . '<br>';
    $nombre = $_SESSION['uname'];
    $fecha = date('Y-m-d');
    $abre = $_SESSION['time'];

    $con = conexion();
    $con->set_charset("utf8mb4");
    echo $fecha = date("Y-m-d");


    $retiro = $_POST['valorARestar'];
    $obs = $_POST['obs'];
    $sql = "SELECT * FROM caja_final ORDER BY id DESC";
    $row = $con->query($sql);
    $saldo = $row->fetch_assoc();

    echo "<br>Saldo actual: " . $saldo_actual = $saldo['saldo_ft'];

    echo "<br>Saldo: " . $actual = $saldo_actual - $retiro;

    echo "<br>Va a quedar en caja: " . $actual;

    //exit;

    $stmt = $con->prepare("INSERT INTO caja_final (retiro_ft, usuario, fecha, observaciones, saldo_ft) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $retiro, $nombre, $fecha, $obs, $actual);
    $stmt->execute();

    header("Location: inicio_salida.php");
}
