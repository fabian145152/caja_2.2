<?php
session_start();


$ruta = "../../licencia/desencriptada.txt";


if (!file_exists($ruta)) {
    die("⛔ No existe el archivo desencriptada.txt");
}

$mes_licencia = (int) trim(file_get_contents($ruta));


$mes_licencia;
$mes_actual = date("m");

#----------------------------------#
#-------bloqueo del sistema--------#
#----------------------------------#
$mes_licencia = (int)$mes_licencia;
$mes_actual   = (int)date('n');


$mes_actual   = (int)$mes_actual;
$mes_licencia = (int)$mes_licencia;

// Calcular mes siguiente
$mes_siguiente = ($mes_actual == 12) ? 1 : $mes_actual + 1;

// Validación
if ($mes_licencia === $mes_siguiente) {
    echo "<script>
        /* alert('✅ Licencia válida.');*/
        location.href = '#seccion1';
    </script>";
} else {

    echo "<script>
        alert('⛔ Licencia inválida o vencida.');
        location.href = '../../../../index.php';
    </script>";
}


#----------------------------------#

?>
<h2 id="seccion1"></h2>

<?php
if (!isset($_SESSION['uname'])) {
    // Si la sesión expiró, redirige al login
    //    header("Location: ../../../../login.php");
    exit;
}



require_once __DIR__ . "/../../licencia/check_licencia.php";


include_once "../../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

$semana_actual = date("W");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobros</title>
    <?php head(); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding-top: 100px;
        }

        form {
            margin: 40px auto;
            display: inline-block;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"] {
            padding: 8px 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-left: 10px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #Power-Contenedor {
            margin-top: 60px;
        }

        #Power-Contenedor button {
            background-color: #dc3545;
        }

        #Power-Contenedor button:hover {
            background-color: #b02a37;
        }
    </style>
</head>

<body>
    <form method="POST" action="cobro_empieza.php">
        <label for="movil">Cobrar a móvil:</label>
        <input type="text" id="movil" name="movil" autofocus required>
        <button type="submit">Continuar</button>
    </form>

    <form method="POST" action="../cobro_tropas/tropas_empieza.php">
        <label for="tropa">Cobrar a tropa:</label>
        <input type="text" id="tropa" name="tropa" required>
        <button type="submit">Continuar</button>
    </form>

    <div id="Power-Contenedor">
        <button onclick="cerrarPagina()">Cerrar página</button>
    </div>

    <?php foot(); ?>

    <script>
        function cerrarPagina() {

            window.close();

        }
    </script>
</body>

</html>