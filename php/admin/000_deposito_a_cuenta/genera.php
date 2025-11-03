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

// 🔒 Usamos consulta preparada para evitar inyección SQL
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
$saldo = $saldo_a_favor - $deuda_anterior;

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depósito a Cuenta</title>
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

        <?php if ($saldo > 0): ?>
            <h3>El saldo del móvil <?php echo htmlspecialchars($row['movil']); ?> es de $<?php echo number_format($saldo_a_favor, 2); ?></h3>
        <?php elseif ($saldo < 0): ?>
            <h3>Tiene una deuda anterior de $<?php echo number_format(abs($deuda_anterior), 2); ?></h3>
        <?php else: ?>
            <h3>El saldo actual es $0</h3>
        <?php endif; ?>

        <ul>
            <li><strong>Móvil:</strong> <?php echo htmlspecialchars($row['movil']); ?></li>
            <li><strong>Nombre:</strong> <?php echo htmlspecialchars($row['nombre_titu']); ?></li>
            <li><strong>Apellido:</strong> <?php echo htmlspecialchars($row['apellido_titu']); ?></li>
        </ul>

        <form class="form" action="guarda_dep_nueva.php" method="POST" name="movil">
            <h1>Ingrese nuevo monto</h1>

            <input type="hidden" name="movil" value="<?php echo htmlspecialchars($movil); ?>">
            <input type="hidden" name="saldo_a_favor" value="<?php echo htmlspecialchars($saldo_a_favor); ?>">
            <input type="hidden" name="deuda_anterior" value="<?php echo htmlspecialchars($deuda_anterior); ?>">

            <input type="text" name="deposito" class="gui-input" placeholder="Monto a depositar" required autofocus>
            <br>
            <input type="submit" value="GUARDAR" class="btn btn-primary">
        </form>

        <button onclick="cerrarPagina()" class="btn btn-primary btn-sm">CERRAR PÁGINA</button>
    </div>

    <?php foot(); ?>
</body>

</html>