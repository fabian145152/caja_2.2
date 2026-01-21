<?php

$archivo_encriptado   = __DIR__ . "/syst_block.txt";
$archivo_desencriptado = __DIR__ . "/desencriptada.txt";
$rutaLicencia         = __DIR__ . "/licencia.ok";

if (!file_exists($archivo_encriptado)) {
    die("⛔ No existe archivo de licencia encriptado");
}

$clave  = hash('sha256', 'MiClaveSegura123', true);
$metodo = "AES-256-CBC";

// Leer y decodificar
$contenido = base64_decode(file_get_contents($archivo_encriptado));

$iv_length    = openssl_cipher_iv_length($metodo);
$iv           = substr($contenido, 0, $iv_length);
$texto_cifrado = substr($contenido, $iv_length);

// Desencriptar
$mes_licencia = openssl_decrypt(
    $texto_cifrado,
    $metodo,
    $clave,
    OPENSSL_RAW_DATA,
    $iv
);

if ($mes_licencia === false || !is_numeric($mes_licencia)) {
    die("⛔ Licencia inválida");
}

// 👉 GUARDAR ARCHIVO DESENCRIPTADO
file_put_contents($archivo_desencriptado, trim($mes_licencia));

// Validación de mes
echo "Mes actual: " . $mes_actual   = (int) date('n'); // 1–12
echo "Mes licencia: " . $mes_licencia = (int) $mes_licencia;



//exit;

// Calcular mes siguiente
$mes_siguiente = $mes_actual + 1;
if ($mes_siguiente === 13) {
    $mes_siguiente = 1;
}

// Validar licencia
if ($mes_licencia !== $mes_actual && $mes_licencia !== $mes_siguiente) {
    die("⛔ LICENCIA VENCIDA");
}

// 👉 SOLO AHORA se habilita el sistema
file_put_contents($rutaLicencia, "ACTIVA");

// Borrar archivo original encriptado
unlink($archivo_encriptado);

echo "<script>
    alert('Licencia válida. Sistema habilitado.');
    location.href = '../cobros/cobro_moviles/inicio_cobros.php?mes_licencia={$mes_licencia}';
</script>";

exit;
