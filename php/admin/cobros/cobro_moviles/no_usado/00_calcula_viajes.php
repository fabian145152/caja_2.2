<?php
// Normalizar variables
$paga_x_semana = round($paga_x_semana ?? 0);
$imp_viaje     = round($paga_x_viaje ?? 0);
$imp_voucher   = round($dato_a_env ?? 0);
$saldoAfavor   = $saldo_a_favor ?? 0;
$movil         = $movil ?? '';
?>

<div class="form-container">
    <form id="calculoForm">
        <div class="form-group">
            <label>Viajes a cobrar:</label>
            <input type="text" id="cant_viajes" oninput="calcular()"
                autofocus style="text-align:center;font-size:18px;font-weight:bold;background:yellow;">
        </div>

        <div class="form-group">
            <label>Semanas postergadas:</label>
            <input type="text" id="postergar_semana" value="0" oninput="calcular()"
                style="text-align:center;font-size:18px;font-weight:bold;background:orange;">
        </div>

        <div class="form-group">
            <label>Resultado:</label>
            <input type="text" id="resultado" readonly
                style="text-align:center;font-size:18px;font-weight:bold;">
        </div>

        <p id="mensaje" style="font-weight:bold;"></p>
    </form>
</div>

<script>
    // Variables PHP → JS
    const imp_viaje = <?= $imp_viaje ?>;
    const imp_voucher = <?= $imp_voucher ?>;
    const saldoAfavor = <?= $saldoAfavor ?>;
    const paga_x_semana = <?= $paga_x_semana ?>;
    const movil = "<?= $movil ?>";

    function calcular() {
        const cantViajes = parseFloat(document.getElementById('cant_viajes').value) || 0;
        const semanas = parseFloat(document.getElementById('postergar_semana').value) || 0;
        const resultado = document.getElementById('resultado');
        const mensaje = document.getElementById('mensaje');

        // Cálculo limpio
        const total = imp_voucher -
            (cantViajes * imp_viaje) +
            saldoAfavor +
            (semanas * paga_x_semana);

        resultado.value = total.toFixed(2);

        if (total < 0) {
            resultado.style.background = "red";
            mensaje.textContent = "Debe abonar";
            mensaje.style.color = "red";
        } else {
            resultado.style.background = "lightgreen";
            mensaje.textContent = "Habrá que depositarle";
            mensaje.style.color = "green";
        }

        // Guardar valores para la página anterior
        localStorage.setItem("resultadoResta", total.toFixed(2));
        localStorage.setItem("postergar_semana", semanas);
        localStorage.setItem("movil", movil);
    }

    window.addEventListener('load', calcular);
</script>
