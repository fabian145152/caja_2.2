<?php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");

// Sanitizamos el id recibido
$id_upd = isset($_GET['q']) ? intval($_GET['q']) : 0;
if ($id_upd <= 0) {
    echo "ID inválido.";
    exit;
}

// Consulta preparada para obtener la fila principal
$sql_movil = "SELECT * FROM completa WHERE id = ?";
$stmt = $con->prepare($sql_movil);
if (!$stmt) {
    echo "Error en la preparación de la consulta.";
    exit;
}
$stmt->bind_param("i", $id_upd);
$stmt->execute();
$result_movil = $stmt->get_result();
$row = $result_movil->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "Registro no encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ACTUALIZAR TITULAR</title>
    <?php head(); ?>
</head>

<body>

    <?php
    // Obtenemos el movil desde la fila ya recuperada
    $movil = isset($row['movil']) ? intval($row['movil']) : 0;

    // Consulta preparada para semanas (por movil)
    $row_semana = null;
    if ($movil > 0) {
        $sql_semana = "SELECT * FROM semanas WHERE movil = ? LIMIT 1";
        $stmt2 = $con->prepare($sql_semana);
        if ($stmt2) {
            $stmt2->bind_param("i", $movil);
            $stmt2->execute();
            $res_sem = $stmt2->get_result();
            $row_semana = $res_sem->fetch_assoc();
            $stmt2->close();
        }
    }

    // Además recuperamos el registro "completa" por movil (para estado_admin)
    $lee_row = null;
    if ($movil > 0) {
        $sql_lee = "SELECT * FROM completa WHERE movil = ? LIMIT 1";
        $stmt3 = $con->prepare($sql_lee);
        if ($stmt3) {
            $stmt3->bind_param("i", $movil);
            $stmt3->execute();
            $res_lee = $stmt3->get_result();
            $lee_row = $res_lee->fetch_assoc();
            $stmt3->close();
        }
    }

    $estado_admin = ($lee_row && isset($lee_row['estado_admin'])) ? intval($lee_row['estado_admin']) : 0;

    // Recuperamos el estado administrativo actual (si existe)
    $lee_estado = null;
    if ($estado_admin > 0) {
        $sql_estado_actual = "SELECT * FROM estados_unidades WHERE id = ? LIMIT 1";
        $stmt4 = $con->prepare($sql_estado_actual);
        if ($stmt4) {
            $stmt4->bind_param("i", $estado_admin);
            $stmt4->execute();
            $res_est_act = $stmt4->get_result();
            $lee_estado = $res_est_act->fetch_assoc();
            $stmt4->close();
        }
    }

    // Recuperamos todos los estados para el select
    $estados = [];
    $sql_estado = "SELECT * FROM estados_unidades ORDER BY nombre";
    $res_est = $con->query($sql_estado);
    if ($res_est) {
        while ($r = $res_est->fetch_assoc()) {
            $estados[] = $r;
        }
    }
    ?>

    <div class="container">
        <h3 class="text-center">ACTUALIZAR DATOS DEL TITULAR</h3>
        <div class="row">
            <div class="col-md-12">

                <!-- accept-charset corregido -->
                <form class="form-group" accept-charset="utf-8" action="update_movil.php" method="post">
                    <!-- único hidden id -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <div class="form-group">
                        <label class="control-label">Movil</label>
                        <input type="text" class="form-control" id="movil" name="movil" value="<?php echo htmlspecialchars($row['movil']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="control-label">TROPA</label>
                        <input type="text" class="form-control" id="tropa" name="tropa" value="<?php echo htmlspecialchars($row['tropa']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_titu" name="nombre_titu" value="<?php echo htmlspecialchars($row['nombre_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido_titu" name="apellido_titu" value="<?php echo htmlspecialchars($row['apellido_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">DNI</label>
                        <input type="text" class="form-control" id="dni_titu" name="dni_titu" value="<?php echo htmlspecialchars($row['dni_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Direccion</label>
                        <input type="text" class="form-control" id="direccion_titu" name="direccion_titu" value="<?php echo htmlspecialchars($row['direccion_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">CP</label>
                        <input type="text" class="form-control" id="cp_titu" name="cp_titu" value="<?php echo htmlspecialchars($row['cp_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Celular</label>
                        <input type="text" class="form-control" id="cel_titu" name="cel_titu" value="<?php echo htmlspecialchars($row['cel_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Licencia</label>
                        <input type="text" class="form-control" id="licencia_titu" name="licencia_titu" value="<?php echo htmlspecialchars($row['licencia_titu']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">ESTADO ADMINISTRATIVO</label>
                        <select id="estado" name="estado" class="form-control">
                            <?php
                            // Si existe estado actual, mostrarlo primero como seleccionado
                            if ($lee_estado) {
                                $id_e = intval($lee_estado['id']);
                                echo '<option value="' . $id_e . '" selected>' . htmlspecialchars($lee_estado['nombre']);
                                if (!empty($lee_estado['color'])) {
                                    echo ' (' . htmlspecialchars($lee_estado['color']) . ')';
                                }
                                echo '</option>';
                            } else {
                                echo '<option value="0" selected>-- Seleccione estado --</option>';
                            }

                            // Listar el resto de estados (si hay coincidencia de id evitamos duplicar)
                            foreach ($estados as $estado_item) {
                                $eid = intval($estado_item['id']);
                                if ($lee_estado && $eid === intval($lee_estado['id'])) {
                                    continue; // ya mostrado como selected
                                }
                                echo '<option value="' . $eid . '">' . htmlspecialchars($estado_item['nombre']);
                                if (!empty($estado_item['color'])) {
                                    echo ' (' . htmlspecialchars($estado_item['color']) . ')';
                                }
                                echo '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- campo oculto con referencia a semana.movil si lo consideras necesario -->
                    <input type="hidden" id="semana_movil" name="semana_movil" value="<?php echo $row_semana ? htmlspecialchars($row_semana['movil']) : ''; ?>">

                    <div class="text-center">
                        <br>
                        <input type="submit" class="btn btn-primary" value="GUARDAR MOVIL">
                        <br><br><br>
                        <a href="lista_movil.php" class="btn btn-secondary">SALIR</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php foot(); ?>
</body>

</html>