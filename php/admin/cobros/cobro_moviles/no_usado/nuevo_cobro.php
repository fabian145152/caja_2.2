<?php
session_start();
include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

// --- Recibir móvil por POST o GET ---
if (!empty($_POST['movil'])) {
    $movil = intval($_POST['movil']);
} elseif (!empty($_GET['movil'])) {
    $movil = intval($_GET['movil']);
} else {
    die("No se recibió el número de móvil.");
}

$_SESSION['movil'] = $movil;

// -----------------------------------------
// 1) Datos del móvil (venta_1, deuda_anterior)
// -----------------------------------------
$sql = "SELECT venta_1, deuda_anterior FROM completa WHERE movil = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $movil);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("El móvil $movil no existe.");
}

$data = $res->fetch_assoc();
$venta_1 = floatval($data['venta_1'] ?? 0);
$deuda_ant = floatval($data['deuda_anterior'] ?? 0);

// -----------------------------------------
// 2) Listado de vouchers
// -----------------------------------------
$sql = "SELECT id, fecha, viaje_no, reloj, peaje, equipaje, adicional, plus 
        FROM voucher_validado 
        WHERE movil = ?
        ORDER BY fecha DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $movil);
$stmt->execute();
$res_v = $stmt->get_result();

$voucher_list = [];
$total_voucher_amount = 0.0;
while ($v = $res_v->fetch_assoc()) {
    // Normalizar valores (evitar nulls)
    $v['reloj']    = floatval($v['reloj'] ?? 0);
    $v['peaje']    = floatval($v['peaje'] ?? 0);
    $v['equipaje'] = floatval($v['equipaje'] ?? 0);
    $v['adicional'] = floatval($v['adicional'] ?? 0);
    $v['plus']     = floatval($v['plus'] ?? 0);

    $v['total'] = $v['reloj'] + $v['peaje'] + $v['equipaje'] + $v['adicional'] + $v['plus'];

    $total_voucher_amount += $v['total'];
    $voucher_list[] = $v;
}

$total_vouchers = count($voucher_list);

// -----------------------------------------
// 3) Semanas (deuda por semanas)
// -----------------------------------------
$sql = "SELECT total FROM semanas WHERE movil = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $movil);
$stmt->execute();
$res_s = $stmt->get_result();
$debe_semanas = floatval($res_s->fetch_assoc()['total'] ?? 0);

// -----------------------------------------
// 4) Cálculos finales
// -----------------------------------------
$descuento_10 = $total_voucher_amount * 0.10;
$voucher_neto  = $total_voucher_amount * 0.90;

$total_sin_vouchers = $venta_1 + $deuda_ant + $debe_semanas;
$total_ajustado = $total_sin_vouchers - $voucher_neto;

function fmon($n)
{
    return number_format($n, 2, ',', '.');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Panel de Cobro - Móvil <?= htmlspecialchars($movil, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body {
            font-family: Segoe UI, Arial;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin: 0 0 16px
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee
        }

        .small {
            font-size: 13px;
            color: #666
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px
        }

        th,
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            text-align: left
        }

        th {
            background: #fafafa
        }

        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 18px
        }

        .btn {
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            font-weight: bold
        }

        .btn-green {
            background: #28a745
        }

        .btn-blue {
            background: #007bff
        }

        .btn-red {
            background: #dc3545
        }

        .btn-del {
            background: #c82333;
            color: #fff;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none
        }

        .total {
            font-weight: bold;
            font-size: 18px
        }

        .total-positivo {
            color: #28a745
        }

        .total-negativo {
            color: #dc3545
        }

        @media print {
            .btn-container {
                display: none
            }
        }
    </style>

    <script>
        function eliminarVoucher(id) {
            if (!confirm("¿Eliminar este voucher?")) return;
            // redirigimos pasando el movil para que recalcule vuelva
            window.location.href = "eliminar_voucher.php?id=" + id + "&movil=<?= $movil ?>";
        }

        function imprimirRecibo() {
            window.print();
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Cobro del Móvil <?= htmlspecialchars($movil, ENT_QUOTES, 'UTF-8') ?></h1>

            <div class="data-row"><span>Ventas del día</span><span class="verde">$ <?= fmon($venta_1) ?></span></div>
            <div class="data-row"><span>Deuda anterior</span><span class="rojo">$ <?= fmon($deuda_ant) ?></span></div>
            <div class="data-row"><span>Semanas adeudadas</span><span class="naranja">$ <?= fmon($debe_semanas) ?></span></div>

            <hr style="border:none;border-top:1px solid #eee;margin:14px 0;">

            <h3>Vouchers (<?= $total_vouchers ?>)</h3>

            <?php if ($total_vouchers === 0): ?>
                <p class="small">No hay vouchers para este móvil.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Viaje</th>
                            <th>Reloj</th>
                            <th>Peaje</th>
                            <th>Equipaje</th>
                            <th>Adicional</th>
                            <th>Plus</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($voucher_list as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['fecha'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($v['viaje_no'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= fmon($v['reloj']) ?></td>
                                <td><?= fmon($v['peaje']) ?></td>
                                <td><?= fmon($v['equipaje']) ?></td>
                                <td><?= fmon($v['adicional']) ?></td>
                                <td><?= fmon($v['plus']) ?></td>
                                <td><?= fmon($v['total']) ?></td>
                                <td><a href="javascript:eliminarVoucher(<?= intval($v['id']) ?>)" class="btn-del">Eliminar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top:12px">
                    <div class="data-row"><span class="small">Importe bruto de vouchers</span><span>$ <?= fmon($total_voucher_amount) ?></span></div>
                    <div class="data-row"><span class="small">Descuento 10% (comisión)</span><span>- $ <?= fmon($descuento_10) ?></span></div>
                    <div class="data-row"><span class="small">Importe neto acreditado (90%)</span><span>$ <?= fmon($voucher_neto) ?></span></div>
                </div>
            <?php endif; ?>

            <hr style="border:none;border-top:1px solid #eee;margin:14px 0;">

            <div class="data-row"><span>Total sin vouchers</span><span>$ <?= fmon($total_sin_vouchers) ?></span></div>

            <?php
            $cls = ($total_ajustado <= 0) ? 'total-positivo' : 'total-negativo';
            $formatted = fmon(abs($total_ajustado));
            ?>
            <div class="data-row total">
                <span>Total ajustado (después de vouchers)</span>
                <span class="<?= $cls ?>">
                    <?php
                    if ($total_ajustado < 0) {
                        echo "- $ $formatted <span class='small' style='color:#666'>&nbsp;(Saldo a favor)</span>";
                    } elseif ($total_ajustado == 0) {
                        echo "$ 0,00 <span class='small' style='color:#666'>&nbsp;(Al día)</span>";
                    } else {
                        echo "$ $formatted";
                    }
                    ?>
                </span>
            </div>

            <div class="btn-container">
                <a href="procesar_cobro.php" class="btn btn-green">Cobrar</a>
                <a href="javascript:imprimirRecibo()" class="btn btn-blue">Imprimir</a>
                <a href="inicio_cobros.php" class="btn btn-red">Cancelar</a>
            </div>

        </div>
    </div>
</body>

</html>