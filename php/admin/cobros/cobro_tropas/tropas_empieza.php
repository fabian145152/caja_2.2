<?php
session_start();

include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");


// Conexión


$tropa = isset($_POST['tropa']) ? (int) $_POST['tropa'] : 0;

if ($tropa === 0) {
    die("Error: No se recibió tropa.");
}

// 1️⃣ Obtener datos de la tropa
$sql_tro = "SELECT * FROM completa WHERE tropa = ? LIMIT 1";
$stmt = $con->prepare($sql_tro);
$stmt->bind_param("i", $tropa);
$stmt->execute();
$dato_tropa = $stmt->get_result()->fetch_assoc();

$movil = isset($_POST['movil']) ? $_POST['movil'] : null;
$apellido_titu = isset($dato_tropa['apellido_titu']) ? $dato_tropa['apellido_titu'] : '';
$obs = isset($dato_tropa['obs']) ? $dato_tropa['obs'] : '';

// 2️⃣ Obtener móviles de la tropa
$sql_moviles = "SELECT movil FROM completa WHERE tropa = ? ORDER BY movil";
$stmt = $con->prepare($sql_moviles);
$stmt->bind_param("i", $tropa);
$stmt->execute();
$res_mov = $stmt->get_result();

$moviles = [];
while ($fila = $res_mov->fetch_assoc()) {
    $moviles[] = $fila['movil'];
}

##---------------------------------------------------------------------------
// 🔹 2b️⃣ Calcular totales de saldo a favor y deuda anterior
$total_saldo_favor = 0;
$total_deuda_anterior = 0;

if (!empty($moviles)) {
    $placeholders = implode(',', array_fill(0, count($moviles), '?'));
    $sql_saldos = "SELECT 
                        SUM(saldo_a_favor_ft) AS total_saldo_favor,
                        SUM(deuda_anterior) AS total_deuda_anterior
                   FROM completa
                   WHERE movil IN ($placeholders)";
    $stmt = $con->prepare($sql_saldos);
    $types = str_repeat('i', count($moviles));
    $stmt->bind_param($types, ...$moviles);
    $stmt->execute();
    $res_saldos = $stmt->get_result()->fetch_assoc();

    $total_saldo_favor = isset($res_saldos['total_saldo_favor']) ? $res_saldos['total_saldo_favor'] : 0;

    $total_deuda_anterior = isset($res_saldos['total_deuda_anterior']) ? $res_saldos['total_deuda_anterior'] : 0;
}

##---------------------------------------------------------------------------



// 3️⃣ Calcular total ajustado de semanas
$total_ajustado = 0;
if (!empty($moviles)) {
    $placeholders = implode(',', array_fill(0, count($moviles), '?'));

    $sql_sem = "SELECT SUM(GREATEST(total - x_semana, 0)) AS total_ajustado
            FROM semanas
            WHERE movil IN ($placeholders)";

    $stmt = $con->prepare($sql_sem);
    $types = str_repeat('i', count($moviles));
    $stmt->bind_param($types, ...$moviles);
    $stmt->execute();
    $res_sem = $stmt->get_result()->fetch_assoc();
    $total_ajustado = isset($res_sem['total_ajustado']) ? $res_sem['total_ajustado'] : 0;
}
//exit;
// 4️⃣ Datos de viajes con abono
$viajes = [];
if (!empty($moviles)) {
    $placeholders = implode(',', array_fill(0, count($moviles), '?'));
    $sql = "SELECT c.movil, c.x_viaje, a.importe, s.x_semana
        FROM completa c
        LEFT JOIN abono_viaje a ON c.x_viaje = a.id
        LEFT JOIN semanas s ON c.movil = s.movil
        WHERE c.movil IN ($placeholders)";
    $stmt = $con->prepare($sql);
    $types = str_repeat('i', count($moviles));
    $stmt->bind_param($types, ...$moviles);
    $stmt->execute();
    $viajes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 5️⃣ Vouchers de cada móvil
$vouchers = [];
$gran_total = 0;
$total_vouchers = 0;

$sql_vou = "SELECT movil, viaje_no, reloj, peaje, equipaje, adicional, plus 
            FROM voucher_validado WHERE movil = ?";

$stmt = $con->prepare($sql_vou);
foreach ($moviles as $movil) {
    $stmt->bind_param("i", $movil);
    $stmt->execute();
    $res_vou = $stmt->get_result();
    while ($row = $res_vou->fetch_assoc()) {
        $tot_voucher = $row['reloj'] + $row['peaje'] + $row['equipaje'] + $row['adicional'] + $row['plus'];
        $row['total'] = $tot_voucher;
        $vouchers[] = $row;
        $gran_total += $tot_voucher;
        $total_vouchers++;
    }
}

// Calcular descuentos
$diez       = $gran_total * 0.10;
$para_tropa = $gran_total - $diez;

// 🔹 Incorporar saldo a favor y deuda anterior
$total_trop = $para_tropa - $total_ajustado + $total_saldo_favor - $total_deuda_anterior;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COBRAR TROPAS</title>
    <?php head() ?>
    <link rel="stylesheet" href="../../../css/vista_con_voucher.css">
    <link rel="stylesheet" href="../cobro_moviles/vista_cobro.css">
    <link rel="stylesheet" href="tropas.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        .zoom-vertical ul,
        .zoom-vertical h4,
        .zoom-vertical h5,
        .zoom-vertical h6 {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="zoom-vertical">
        <br>
        <form action="deposito_tropas.php" method="post" id="formulario">
            <ul style="border: 2px solid black; padding: 10px; border-radius: 10px; list-style-type: none;">
                <div id="contenedor">
                    <h4>Estado de cuenta de la <strong>TROPA:</strong> <?= htmlspecialchars($tropa) ?>.</h4>
                    <h5>
                        Fecha: <?= date("d/m/Y") ?> -
                        Semana <?= date('W') - 1 ?>
                    </h5>
                    <?php
                    // Inicializar variables por seguridad
                    $nombre_titu   = isset($dato_tropa['nombre_titu']) ? $dato_tropa['nombre_titu'] : '';
                    $apellido_titu = isset($dato_tropa['apellido_titu']) ? $dato_tropa['apellido_titu'] : '';
                    ?>
                    <h6><strong>TITULAR:</strong> <?php echo htmlspecialchars($nombre_titu . " " . $apellido_titu); ?></h6>


                    <div class="column left-column">
                        <strong>COMENTARIOS:</strong> <?= htmlspecialchars($obs) ?>
                    </div>
                </div>
            </ul>
    </div>
    <div class="recuadros-container">
        <ul class="recuadro">
            <h3>UNIDADES</h3>
            <h4>ABONOS:</h4>
            <p>
                <strong>Saldo a favor FT:</strong> <?= number_format($total_saldo_favor, 2, ',', '.') ?><br>
                <strong>Deuda anterior:</strong> <?= number_format($total_deuda_anterior, 2, ',', '.') ?><br>
                <strong>Total neto:</strong> <?= number_format($total_saldo_favor - $total_deuda_anterior, 2, ',', '.') ?>
            </p>

            <?php if (!empty($viajes)): ?>
                <table border="1" cellpadding="5">
                    <tr>
                        <th>Movil</th>
                        <th>X Viaje</th>
                        <th>x Semana</th>
                    </tr>
                    <?php foreach ($viajes as $row): ?>
                        <tr>
                            <td><?= $row['movil'] ?></td>
                            <td><?= number_format($row['importe'], 2, ',', '.') ?></td>
                            <td><?= number_format($row['x_semana'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No se encontraron registros para los móviles indicados.</p>
            <?php endif; ?>
        </ul>
        <!-- 🔹 Vouchers -->
        <ul class="recuadro">

            <h3>VOUCHER</h3>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Movil</th>
                    <th>Viaje_no</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($vouchers as $row): ?>
                    <tr>
                        <td><?= $row['movil'] ?></td>
                        <td><?= $row['viaje_no'] ?></td>
                        <td><?= number_format($row['total'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background:#f0f0f0;">
                    <td colspan="2">TOTAL DE VOUCHER</td>
                    <td><?= number_format($gran_total, 2, ',', '.') ?></td>
                </tr>
                <tr style="background:#e0ffe0;">
                    <td colspan="2">DESCUENTOS</td>
                    <td><?= number_format($para_tropa, 2, ',', '.') ?></td>
                </tr>
                <tr style="background:#e0ffe0;">
                    <td colspan="2">Total de Semanas</td>
                    <td><?= number_format($total_ajustado, 2, ',', '.') ?></td>
                </tr>
                <tr style="background:#e0ffe0;">
                    <td colspan="3">VIAJES A VALIDAR</td>
                </tr>
                <?php foreach ($viajes as $row): ?>
                    <tr>
                        <td><?= $row['movil'] ?></td>
                        <td>
                            <input type="number"
                                name="viajes_validados[<?= $row['movil'] ?>]"
                                class="viaje-input"
                                data-importe="<?= isset($row['importe']) ? $row['importe'] : 0 ?>"
                                value="0"
                                style="width:60px; text-align:center;" autofocus>
                        </td>
                        <td><?= number_format($row['importe'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr style="background:#ffe0e0;">
                    <td colspan="2">
                        <h4>TOTAL</h4>
                    </td>
                    <td>
                        <h4 id="total_final"><?= number_format($total_trop, 2, ',', '.') ?></h4>
                        <input type="hidden" id="total_trop" name="total_trop" value="<?php echo $total_trop ?>">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h3>Deposito FT</h3>
                        <input type="text" id="dep_ft" name="dep_ft" style="text-align: center;" required>
                    </td>
                </tr>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const inputs = document.querySelectorAll(".viaje-input");
                        const totalFinal = document.getElementById("total_final");
                        const totalInput = document.getElementById("total_trop"); // 🔹 input oculto que se enviará al backend
                        let totalBase = <?= json_encode($total_trop) ?>;

                        function recalcular() {
                            let descuentoTotal = 0;

                            // 🔹 Suma los importes según la cantidad de viajes validados
                            inputs.forEach(input => {
                                let cantidad = parseInt(input.value) || 0;
                                let importe = parseFloat(input.dataset.importe) || 0;
                                descuentoTotal += cantidad * importe;
                            });

                            // 🔹 Nuevo total de la tropa
                            let nuevoTotal = totalBase - descuentoTotal;

                            // 🔹 Actualiza el texto visible del total
                            totalFinal.textContent = nuevoTotal.toLocaleString("es-AR", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            // 🔹 Actualiza el input oculto (valor que va a deposito_tropas.php)
                            if (totalInput) {
                                totalInput.value = nuevoTotal.toFixed(2);
                            }
                        }

                        // 🔹 Recalcula cada vez que el usuario sale de un input (blur) o cambia un valor
                        inputs.forEach(input => {
                            input.addEventListener("blur", recalcular);
                            input.addEventListener("input", recalcular);
                        });
                    });

                    // 🔹 Confirma antes de enviar el formulario
                    function confirmarAccion(event) {
                        let ok = confirm("¿Confirmás el depósito y la liquidación de la tropa?");
                        if (!ok) {
                            event.preventDefault(); // ✋ Detiene el envío del form
                        }
                    }
                </script>

            </table>


            <!-- Hidden inputs -->
            <input type="hidden" name="tropa" value="<?= htmlspecialchars($tropa) ?>">
            <?php foreach ($moviles as $movil): ?>
                <input type="hidden" name="moviles[]" value="<?= htmlspecialchars($movil) ?>">
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="recuadro-botones">
        <div class="botonera">
            <button type="submit" onclick="confirmarAccion(event)">DEPOSITAR</button>
        </div>
    </div>
    </form>
    <a href="../cobro_moviles/inicio_cobros.php">VOLVER</a>
</body>

</html>