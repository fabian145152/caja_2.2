<?php
session_start();
include_once "../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

// --- Validar parámetro GET ---
if (!isset($_GET['movil']) || empty($_GET['movil'])) {
    echo "<script>alert('No se recibió un número de móvil válido.'); history.back();</script>";
    exit;
}

$mensaje = '';
$mostrar_alerta = false;
$movil_param = trim($_GET['movil']);

// --- Procesar actualización ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_cant') {

    $movil_post = trim(isset($_POST['movil']) ? $_POST['movil'] : '');
    $cant_semanas = trim(isset($_POST['cant_semanas']) ? $_POST['cant_semanas'] : '');
    $x_semana = str_replace(',', '.', trim(isset($_POST['x_semana']) ? $_POST['x_semana'] : ''));



    if ($movil_post === '') {
        $mensaje = "Número de móvil inválido.";
    } elseif (!ctype_digit($cant_semanas)) {
        $mensaje = "La cantidad de semanas debe ser un número entero.";
    } elseif (!is_numeric($x_semana)) {
        $mensaje = "El valor por semana no es válido.";
    } else {
        $cant_semanas = intval($cant_semanas);

        // 👇 Guardar una semana más que la indicada
        $cant_guardar = $cant_semanas + 1;

        $x_semana = floatval($x_semana);
        $total = $cant_guardar * $x_semana;

        $sql_up = "UPDATE semanas SET total = ? WHERE movil = ?";
        if ($stmt_up = $con->prepare($sql_up)) {
            $stmt_up->bind_param('ds', $total, $movil_post);
            if ($stmt_up->execute()) {
                if ($stmt_up->affected_rows > 0) {
                    //$mensaje = "Se registraron $cant_guardar semanas para el móvil $movil_post (mostrando $cant_semanas).";
                    $mensaje = "Se registraron $cant_semanas semanas para el móvil $movil_post .";
                    $mostrar_alerta = true; // ✅ Activar alerta de éxito
                } else {
                    $mensaje = "No se realizaron cambios.";
                }
            } else {
                $mensaje = "Error al ejecutar la actualización: " . $stmt_up->error;
            }
            $stmt_up->close();
        } else {
            $mensaje = "Error en prepare(): " . $con->error;
        }
    }
}

// --- Obtener datos combinados ---
$sql = "
    SELECT
        c.movil AS movil,
        c.nombre_titu AS nombre_titu,
        c.apellido_titu AS apellido_titu,
        s.x_semana AS x_semana,
        s.total AS total,
        CASE 
            WHEN s.x_semana IS NOT NULL AND s.x_semana <> 0 THEN ROUND(s.total / s.x_semana) - 1
            ELSE NULL
        END AS cant_semanas
    FROM completa c
    LEFT JOIN semanas s ON c.movil = s.movil
    WHERE c.movil = ?
    ORDER BY c.movil
";

$stmt = $con->prepare($sql);
if (!$stmt) {
    die("Error en prepare(): " . $con->error);
}

$stmt->bind_param("s", $movil_param);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Error en get_result(): " . $con->error);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Semanas adeudadas</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <a href="inicio_bonifica.php">← VOLVER</a>
    <h2>Semanas adeudadas del móvil: <?php echo htmlspecialchars($movil_param); ?></h2>

    <?php if ($mensaje !== ''): ?>
        <?php $isError = preg_match('/error|inválido/i', $mensaje); ?>
        <div class="msg <?php echo $isError ? 'err' : 'ok'; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Móvil</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>X Semana</th>
                <th>Cantidad de semanas</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $movil_row = $row['movil'];
                $nombre = $row['nombre_titu'];
                $apellido = $row['apellido_titu'];
                $x_semana = $row['x_semana'];
                $cant_semanas = isset($row['cant_semanas']) ? $row['cant_semanas'] : '';
                ?>
                <tr>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="update_cant">
                        <input type="hidden" name="movil" value="<?php echo htmlspecialchars($movil_row); ?>">
                        <input type="hidden" name="x_semana" value="<?php echo htmlspecialchars($x_semana); ?>">

                        <td><?php echo htmlspecialchars($movil_row); ?></td>
                        <td><?php echo htmlspecialchars($nombre); ?></td>
                        <td><?php echo htmlspecialchars($apellido); ?></td>
                        <td><?php echo htmlspecialchars($x_semana); ?></td>
                        <td>
                            <input type="number" name="cant_semanas" min="0" step="1"
                                value="<?php echo htmlspecialchars($cant_semanas); ?>"
                                placeholder="0">
                        </td>
                        <td><button type="submit" class="btn-update">Guardar</button></td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php foot(); ?>

    <?php if ($mostrar_alerta): ?>
        <script>
            alert("✅ Semanas actualizadas correctamente");
        </script>
    <?php endif; ?>
</body>

</html>
<?php
$result->free();
$con->close();
?>