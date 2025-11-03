<?php
session_start();
include_once "../../../funciones/funciones.php";

$con = conexion();
$con->set_charset("utf8mb4");

$movil = $_POST['movil'] ?? null;

if (!$movil) {
    die("No se recibió el número de móvil.");
}

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// 🔒 Consulta preparada
$stmt = $con->prepare("SELECT * FROM completa WHERE movil = ?");
$stmt->bind_param("s", $movil);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<h3>No se encontró el móvil especificado.</h3>");
}

$row = $result->fetch_assoc();
$saldo_a_favor = $row['saldo_a_favor_ft'] ?? 0;
$deuda_anterior = $row['deuda_anterior'] ?? 0;

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Saldo a Favor</title>
    <?php head(); ?>

    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>

    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h3 {
            margin-bottom: 15px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            text-align: left;
        }

        ul li {
            margin-bottom: 5px;
        }

        .gui-input {
            padding: 10px;
            width: 220px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0069d9;
        }

        button {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">

        <h3>Editar saldo del móvil <?php echo htmlspecialchars($row['movil']); ?></h3>

        <ul>
            <li><strong>Nombre:</strong> <?php echo htmlspecialchars($row['nombre_titu']); ?></li>
            <li><strong>Apellido:</strong> <?php echo htmlspecialchars($row['apellido_titu']); ?></li>
            <li><strong>Saldo actual:</strong> $<?php echo number_format($saldo_a_favor, 2); ?></li>
            <li><strong>Deuda anterior:</strong> $<?php echo number_format($deuda_anterior, 2); ?></li>
        </ul>

        <form class="form" action="actualiza_saldo.php" method="POST" name="editarSaldo">
            <h1>Nuevo saldo a favor</h1>

            <input type="hidden" name="movil" value="<?php echo htmlspecialchars($movil); ?>">

            <input type="number" step="0.01" name="saldo_a_favor" class="gui-input"
                value="<?php echo htmlspecialchars($saldo_a_favor); ?>" required autofocus>

            <br>
            <input type="submit" value="GUARDAR CAMBIOS" class="btn btn-primary">
        </form>

        <button onclick="cerrarPagina()" class="btn btn-primary btn-sm">CERRAR PÁGINA</button>
    </div>

    <?php foot(); ?>
</body>

</html>