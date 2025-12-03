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
                    $mensaje = "Se registraron $cant_semanas semanas para el móvil $movil_post.";
                    $mostrar_alerta = true;
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            padding: 30px 40px;
            width: 90%;
            max-width: 900px;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            align-self: flex-start;
            margin-bottom: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }

        input[type="number"] {
            width: 80px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn-update {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-update:hover {
            background-color: #0069d9;
        }

        .msg {
            margin: 15px 0;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
        }

        .msg.ok {
            background-color: #d4edda;
            color: #155724;
        }

        .msg.err {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            th {
                display: none;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="container">
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
                    <tr>
                        <form method="post" action="">
                            <input type="hidden" name="action" value="update_cant">
                            <input type="hidden" name="movil" value="<?php echo htmlspecialchars($row['movil']); ?>">
                            <input type="hidden" name="x_semana" value="<?php echo htmlspecialchars($row['x_semana']); ?>">

                            <td data-label="Móvil"><?php echo htmlspecialchars($row['movil']); ?></td>
                            <td data-label="Nombre"><?php echo htmlspecialchars($row['nombre_titu']); ?></td>
                            <td data-label="Apellido"><?php echo htmlspecialchars($row['apellido_titu']); ?></td>
                            <td data-label="X Semana"><?php echo htmlspecialchars($row['x_semana']); ?></td>
                            <td data-label="Cantidad">
                                <input type="number" name="cant_semanas" min="0" step="1"
                                    value="<?php echo isset($row['cant_semanas']) ? htmlspecialchars($row['cant_semanas']) : ''; ?>">
                            </td>
                            <td data-label="Acción"><button type="submit" class="btn-update">Guardar</button></td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if ($mostrar_alerta): ?>
        <script>
            alert("✅ Semanas actualizadas correctamente");
        </script>
    <?php endif; ?>

    <?php foot(); ?>
</body>

</html>

<?php
$result->free();
$con->close();
?>