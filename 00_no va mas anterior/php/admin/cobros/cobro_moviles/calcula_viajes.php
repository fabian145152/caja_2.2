<?php
// Variables PHP (asegurarse que existan)
$paga_x_semana = isset($paga_x_semana) ? round($paga_x_semana) : 0;
$imp_viaje = isset($paga_x_viaje) ? round($paga_x_viaje) : 0;
$imp_voucher = isset($dato_a_env) ? round($dato_a_env) : 0;
$saldoAfavor = isset($saldo_a_favor) ? $saldo_a_favor : 0;
$movil = isset($movil) ? $movil : '';
?>

<div class="form-container">
    <form id="calculoForm">
        <div class="form-group">
            <label for="cant_viajes">Viajes a cobrar:</label>
            <input type="text" id="cant_viajes" name="cant_viajes" oninput="calcularYRestar()" autofocus
                style='text-align:center; font-size:18px; font-weight:bold; background-color: yellow;'>
        </div>

        <div class="form-group">
            <label for="postergar_semana">Semanas postergadas:</label>
            <input type="text" id="postergar_semana" name="postergar_semana" oninput="calcularYRestar()" value="0"
                style='text-align:center; font-size:18px; font-weight:bold; background-color:orange;'>
        </div>

        <div class="form-group">
            <label for="resultadoResta">Resultado:</label>
            <input type="text" id="resultadoResta" name="resultadoResta" readonly
                style='text-align:center; font-size:18px; font-weight:bold;'>
        </div>

        <p id="mensajeResultado" style="font-weight:bold;"></p>
    </form>
</div>

<script>
    // Variables desde PHP
    let imp_viaje = <?= $imp_viaje ?>;
    let imp_voucher = <?= $imp_voucher ?>;
    let saldoAfavor = <?= $saldoAfavor ?>;
    let paga_x_semana = <?= $paga_x_semana ?>;

    function calcularYRestar() {
        const cant_viajes = parseFloat(document.getElementById('cant_viajes').value) || 0;
        const semanasPostergadas = parseFloat(document.getElementById('postergar_semana').value) || 0;
        const campoResultado = document.getElementById('resultadoResta');
        const mensaje = document.getElementById('mensajeResultado');

        const resultadoMultiplicacion = cant_viajes * imp_viaje;
        const adicionalPorSemana = semanasPostergadas * paga_x_semana;
        const resultadoResta = imp_voucher - resultadoMultiplicacion + saldoAfavor + adicionalPorSemana;

        campoResultado.value = resultadoResta.toFixed(2);

        if (resultadoResta < 0) {
            campoResultado.style.backgroundColor = "red";
            mensaje.textContent = "Debe abonar";
            mensaje.style.color = "red";
        } else {
            campoResultado.style.backgroundColor = "lightgreen";
            mensaje.textContent = "Habrá que depositarle";
            mensaje.style.color = "green";
        }

        // Guardar automáticamente en la página anterior usando localStorage
        localStorage.setItem('resultadoResta', campoResultado.value);
        localStorage.setItem('postergar_semana', semanasPostergadas);
        localStorage.setItem('movil', '<?= $movil ?>'); // si necesitas el móvil
    }

    // Ejecutar al cargar la página para inicializar valores en la página anterior
    window.addEventListener('load', calcularYRestar);
</script>

<style>
    .form-group {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .form-group label {
        width: 180px;
        font-weight: bold;
    }

    .form-group input {
        flex: 1;
        padding: 5px;
    }

    .form-container {
        max-width: 500px;
        margin: auto;
        padding: 20px;
    }

    #resultadoResta {
        font-weight: bold;
    }
</style>