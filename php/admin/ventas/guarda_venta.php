<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

if (!isset($_POST['movil'])) {
    die("Falta móvil.");
}

$movil = (int) $_POST['movil'];

// Obtener las nuevas selecciones (array de IDs)
$opciones = isset($_POST['opciones']) && is_array($_POST['opciones']) ? $_POST['opciones'] : [];

// Convertir a ints, limpiar duplicados
$opciones_int = [];
foreach ($opciones as $v) {
    $id = (int)$v;
    if ($id > 0 && !in_array($id, $opciones_int)) {
        $opciones_int[] = $id;
    }
}
$opciones_int = array_slice($opciones_int, 0, 5);

// === Obtener ventas anteriores ===
$stmt_prev = $con->prepare("SELECT venta_1, venta_2, venta_3, venta_4, venta_5 FROM completa WHERE movil = ?");
$stmt_prev->bind_param("i", $movil);
$stmt_prev->execute();
$res_prev = $stmt_prev->get_result();
$row_prev = $res_prev->fetch_assoc();
$stmt_prev->close();

if (!$row_prev) {
    die("No se encontró el cliente con el móvil indicado.");
}

// Ventas anteriores como array
$ventas_prev = array_filter(array_map('intval', [
    $row_prev['venta_1'],
    $row_prev['venta_2'],
    $row_prev['venta_3'],
    $row_prev['venta_4'],
    $row_prev['venta_5']
]));

// === Calcular diferencias ===
$nuevas = array_diff($opciones_int, $ventas_prev); // nuevas ventas
$anuladas = array_diff($ventas_prev, $opciones_int); // ventas anuladas

// === Comenzar transacción ===
$con->begin_transaction();

try {
    // Descontar stock de nuevas ventas
    if (!empty($nuevas)) {
        $stmt_desc = $con->prepare("UPDATE productos SET stock = stock - 1 WHERE id = ? AND stock > 0");
        foreach ($nuevas as $idv) {
            $stmt_desc->bind_param("i", $idv);
            $stmt_desc->execute();
            if ($stmt_desc->affected_rows === 0) {
                throw new Exception("No hay stock disponible para el producto ID $idv.");
            }
        }
        $stmt_desc->close();
    }

    // Devolver stock de ventas anuladas
    if (!empty($anuladas)) {
        $stmt_sum = $con->prepare("UPDATE productos SET stock = stock + 1 WHERE id = ?");
        foreach ($anuladas as $ida) {
            $stmt_sum->bind_param("i", $ida);
            $stmt_sum->execute();
        }
        $stmt_sum->close();
    }

    // Preparar los valores finales para los 5 campos
    $ventas = [0, 0, 0, 0, 0];
    for ($i = 0; $i < count($opciones_int); $i++) {
        $ventas[$i] = $opciones_int[$i];
    }

    // Actualizar la tabla completa
    $sql = "UPDATE completa SET venta_1 = ?, venta_2 = ?, venta_3 = ?, venta_4 = ?, venta_5 = ? WHERE movil = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiiiii", $ventas[0], $ventas[1], $ventas[2], $ventas[3], $ventas[4], $movil);
    $stmt->execute();
    $stmt->close();

    // Confirmar todo
    $con->commit();

    // Redirigir para recargar vista
    header("Location: inicio_ventas.php");
    exit;
} catch (Exception $e) {
    $con->rollback();
    echo "<h3>Error al guardar:</h3> " . htmlspecialchars($e->getMessage());
}
