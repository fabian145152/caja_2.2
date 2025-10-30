<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

$movil = isset($_POST['movil']) ? (int)$_POST['movil'] : 0;
$row_comp = null;
$res_venta = null;
$ventas_prev_ids = [];

if ($movil > 0) {
    // Obtener cliente de forma segura
    $stmt = $con->prepare("SELECT * FROM completa WHERE movil = ?");
    $stmt->bind_param("i", $movil);
    $stmt->execute();
    $res_comp = $stmt->get_result();
    $row_comp = $res_comp->fetch_assoc();
    $stmt->close();

    if ($row_comp) {
        // Obtener productos
        $res_venta = $con->query("SELECT * FROM productos ORDER BY id");

        // Recolectar ventas anteriores (ids)
        $ventas_prev = array_filter([
            $row_comp['venta_1'],
            $row_comp['venta_2'],
            $row_comp['venta_3'],
            $row_comp['venta_4'],
            $row_comp['venta_5']
        ], function ($v) {
            return !empty($v) && $v != 0;
        });

        // convertir a array de ints
        foreach ($ventas_prev as $id) {
            $ventas_prev_ids[] = (int)$id;
        }
    } else {
        $error = "Cliente no encontrado con el móvil " . htmlspecialchars($movil) . ".";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas de Productos</title>
    <?php head(); ?>
    <style>
        body {
            margin: 30px;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        form.search {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        input[type="text"] {
            padding: 6px;
            width: 180px;
        }

        .selected-count {
            margin-left: 10px;
            font-size: 0.9rem;
            color: #555;
        }

        table {
            margin-top: 12px;
        }

        .btn-inline {
            display: inline-block;
            margin-right: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            background: #e9ecef;
            margin-left: 8px;
            font-size: 0.85rem;
        }

        .small-note {
            font-size: 0.9rem;
            color: #333;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="container">

        <form method="post" action="" class="search">
            <h1>Venta de Productos</h1>
            &nbsp;&nbsp;&nbsp;
            <label for="movil">Ingrese Móvil:</label>
            <input type="text" id="movil" name="movil" autofocus pattern="\d+" title="Ingrese solo números" required
                value="<?= $movil > 0 ? htmlspecialchars($movil) : '' ?>">
            <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            <?php if ($movil > 0 && $row_comp): ?>
                <span class="badge">Cliente cargado</span>
            <?php endif; ?>
            <a href="../../menu_admin.php">SALIR</a>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($row_comp): ?>
            <h3>
                Nombre: <?= htmlspecialchars($row_comp['nombre_titu'] . " " . $row_comp['apellido_titu']) ?>
                - Móvil: <?= htmlspecialchars($row_comp['movil']) ?>
            </h3>

            <div class="small-note">
                Las compras anteriores aparecen tildadas en la planilla. Si desmarcás una casilla y guardás, esa compra anterior se **anula**.
            </div>

            <form action="guarda_venta.php" method="post" id="form-ventas">
                <input type="hidden" name="movil" value="<?= htmlspecialchars($row_comp['movil']) ?>">

                <table class="table table-bordered table-sm table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th style="text-align:center">Seleccionar</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_venta = $res_venta->fetch_assoc()):
                            $pid = (int)$row_venta['id'];
                            $checked = in_array($pid, $ventas_prev_ids) ? 'checked' : '';
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row_venta['nombre']) ?></td>
                                <td style="text-align:center">
                                    <input type="checkbox" name="opciones[]" value="<?= $pid ?>" <?= $checked ?>>
                                </td>
                                <td><?= htmlspecialchars($row_venta['descripcion']) ?></td>
                                <td><?= htmlspecialchars($row_venta['precio']) ?></td>
                                <td><?= htmlspecialchars($row_venta['stock']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <button type="submit" class="btn btn-danger btn-sm btn-inline">GUARDAR</button>


                <span class="selected-count" id="selectedCount">Seleccionados: 0</span>
            </form>

        <?php endif; ?>
    </div>

    <script>
        // Contador dinámico de casillas tildadas
        (function() {
            const form = document.getElementById('form-ventas');
            const counter = document.getElementById('selectedCount');
            if (!form) return;

            function updateCount() {
                const checked = form.querySelectorAll('input[type=checkbox][name="opciones[]"]:checked').length;
                counter.textContent = 'Seleccionados: ' + checked;
            }
            form.addEventListener('change', updateCount);
            // inicializar
            updateCount();
        })();
    </script>

    <?php foot(); ?>
</body>

</html>