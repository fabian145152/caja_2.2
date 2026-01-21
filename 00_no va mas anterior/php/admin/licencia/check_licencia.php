<?php
$rutaLicencia = __DIR__ . "/licencia.ok";

$archivo_desencriptado = __DIR__ . "/desencriptada.txt";



if (!file_exists($rutaLicencia)) {
    die("⛔ SISTEMA BLOQUEADO x archivo licencia.ok");
}

if (!file_exists($archivo_desencriptado)) {
    die("⛔ SISTEMA BLOQUEADO x archivo desencriptada.txt");
}

