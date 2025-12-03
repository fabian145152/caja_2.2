<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Voucher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 22px;
        }

        label {
            display: block;
            margin-bottom: 3px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 6px 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div>

        <form action="guardar_voucher.php" method="post" target="_blank">
            <h2>Guardar Voucher manualmente</h2>

            <label for="movil">Móvil:</label>
            <input type="text" id="movil" name="movil" required>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>

            <label for="viaje">Viaje N°:</label>
            <input type="text" id="viaje" name="viaje_no" required>

            <label for="cc">C/C:</label>
            <input type="text" id="cc" name="cc">

            <label for="reloj">Reloj:</label>
            <input type="text" id="reloj" name="reloj">

            <label for="peaje">Peaje:</label>
            <input type="text" id="peaje" name="peaje">

            <label for="equipaje">Equipaje:</label>
            <input type="text" id="equipaje" name="equipaje">

            <label for="adicional">Adicional:</label>
            <input type="text" id="adicional" name="adicional">

            <label for="plus">Plus:</label>
            <input type="text" id="plus" name="plus">

            <button type="submit">GUARDAR</button>
        </form>

        <button onclick="window.close()">Cerrar esta ventana</button>
    </div>



</body>

</html>