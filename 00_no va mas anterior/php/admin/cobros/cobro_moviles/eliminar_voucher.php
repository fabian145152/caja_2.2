<?php
include_once "../../../../funciones/funciones.php";

$con = conexion();

$id = intval($_POST["id"]);

$sql = "DELETE FROM voucher_validado WHERE id = " . $id;

if ($con->query($sql)) {
    echo "OK";
} else {
    echo "ERROR";
}
