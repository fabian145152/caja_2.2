<?php
// upload_licencia.php
session_start();
include_once "../../../funciones/funciones.php";
$con = conexion();
$con->set_charset("utf8mb4");


$targetDir = __DIR__ . DIRECTORY_SEPARATOR; // misma carpeta
$targetFile = $targetDir . 'syst_block.txt';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] === UPLOAD_ERR_NO_FILE) {
        $message = 'No se subió ningún archivo.';
    } else {
        $f = $_FILES['fileToUpload'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $message = 'Error al subir (código: ' . $f['error'] . ').';
        } else {
            $originalName = basename($f['name']);
            if ($originalName !== 'syst_block.txt') {
                $message = 'El archivo debe llamarse exactamente syst_block.txt';
            } else {
                if (move_uploaded_file($f['tmp_name'], $targetFile)) {
                    $message = 'Archivo subido correctamente.';
                } else {
                    $message = 'Error al mover el archivo al destino.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Licencia</title>
    <?php head() ?>
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }

        .message {
            margin: 12px 0;
            padding: 10px;
            border-radius: 6px;
            background: #f5f5f5;
        }
    </style>
</head>

<body>
    <h1>Subir licencia</h1>

    <div class="box">
        <?php if ($message): ?>
            <div class="message">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="fileToUpload">Selecciona syst_block.txt.txt:</label><br><br>
            <input type="file" name="fileToUpload" id="fileToUpload" accept=".txt" required>
            <br><br>
            <button type="submit" class="btn btn-primary btn-sm">Subir</button>
        </form>
    </div>

    <hr>
    <p>Ver el archivo actual:</p>
    <ul>
        <?php if (file_exists($targetFile)): ?>
            <li><a href="syst_block.txt" class="btn btn-primary btn-sm" target="_blank">syst_block.txt</a></li>
        <?php else: ?>
            <li>No hay syst_block.txt en la carpeta.</li>
        <?php endif; ?>
        <br><br><br>
        <a href="desencriptar.php" class="btn btn-success btn-sm">DESBLOQUEAR</a>
        <br><br><br>
        <button onclick="cerrarPagina()" class="btn btn-danger btn-sm">CERRAR PAGINA</button>
        <script>
            function cerrarPagina() {
                window.close();
            }
        </script>
    </ul>
</body>

</html>