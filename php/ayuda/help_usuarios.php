<?php
include_once "../../funciones/funciones.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda de Cobros</title>
    <?php head(); ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 20px;
            color: #333;
        }

        h1,
        h2 {
            color: #2a2a2a;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-cerrar {
            padding: 10px 18px;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .btn-cerrar:hover {
            background-color: #c9302c;
        }

        ul {
            line-height: 1.7;
        }

        li {
            margin-bottom: 4px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .indice {
            background: #eef1f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .indice ul li {
            margin-bottom: 6px;
        }

        section {
            margin-top: 40px;
        }

        .volver {
            padding: 10px 18px;
            background-color: #5a6268;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 40px;
        }

        .volver:hover {
            background-color: #444c52;
        }
    </style>
</head>

<body>

    <div class="container">

        <button class="btn-cerrar" onclick="cerrarPagina()">Cerrar Página</button>

        <!-- =========================== -->
        <!-- ÍNDICE -->
        <!-- =========================== -->
        <div class="indice">
            <h2>Índice de Ayuda</h2>
            <ul>
                <li><a href="#crear_usuarios">1_ Creación de usuarios</a></li>
                <li><a href="#licencia">2_ Licencia del sistema</a></li>
                <li><a href="#crear_unidad">3_ Creación de unidades</a></li>
                <li><a href="#crear_tropa">4_ Tropas</a></li>
                <li><a href="#edicion_uni_comp">5_ Edición de unidad completa</a></li>
                <li><a href="#eliminar_unidad">6_ Eliminar unidad</a></li>
                <li><a href="#obs">7_ Observaciones</a></li>
                <li><a href="#voucher">8_ Voucher</a></li>
                <li><a href="#voucher_manual">9_ Cargar voucher manualmente</a></li>
                <li><a href="#productos">10_ Productos</a></li>
                <li><a href="#venta">11_ Venta a unidades</a></li>
                <li><a href="#tarifas">12_ Tarifas</a></li>
                <li><a href="#viajes">13_ Importe de viajes</a></li>
                <li><a href="#semanas">14_ Abonos semanales</a></li>
                <li><a href="#cobrar_moviles_tropas">15_ Cobrar a móviles y tropas</a></li>
                <li><a href="#bonifica_semanas">16_ Editar semanas</a></li>
                <li><a href="#bonifica_deuda">17_ Bonificar deuda</a></li>
                <li><a href="#genera_deuda">18_ Generar deuda</a></li>
                <li><a href="#dep_a_cuenta">19_ Depósito a cuenta</a></li>
                <li><a href="#edit_sal_a_fav">20_ Editar saldo a favor</a></li>
                <li><a href="#historial_pagos_mov">21_ Historial de pagos</a></li>
                <li><a href="#resumen_caja">22_ Resumen de caja</a></li>
                <li><a href="#extracciones">23_ Extracciones de caja</a></li>
            </ul>
        </div>

        <!-- =========================== -->
        <!-- SECCIONES -->
        <!-- =========================== -->

        <section id="crear_usuarios">
            <h1>1_ Creación de usuarios</h1>
            <ul>
                <li>Crear usuarios con contraseña y correo.</li>
                <li>Los usuarios podrán:</li>
                <ul>
                    <li>Crear/editar unidades</li>
                    <li>Crear tropas</li>
                    <li>Cobrar a móviles y tropas</li>
                    <li>Crear productos y venderlos</li>
                    <li>Editar viajes, semanas y tarifas</li>
                    <li>Generar/bonificar deudas</li>
                </ul>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="licencia">
            <h1>2_ Licencia del sistema</h1>
            <ul>
                <li>El sistema requiere licencia mensual.</li>
                <li>Contactar administrador.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="crear_unidad">
            <h1>3_ Creación de unidades nuevas</h1>
            <ul>
                <li>Es el primer paso para crear un titular unidades y cobrarle</li>
                <li>Ingresar número de móvil.</li>
                <li>Si ya existe, mostrará alerta.</li>
                <li>Completar titular o tropa.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="crear_tropa">
            <h1>4_ Tropas</h1>
            <ul>
                <li>La unidad de menor número es el titular.</li>
                <li>Desde allí se administran sus saldos.</li>
                <li>En el caso de que cuando se le cobre a la tropa y quede deuda o saldo a favor, ese monto ira a la unidad que tenga el numero mkas bajo.</li>
                <li>Si la unidad con el numero de móvil mas bajo de da de baja del sistema, la deuda o el saldo iran a la siguiente unidad con el numero de móvil mas bajo,</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="edicion_uni_comp">
            <h1>5_ Edición de unidad completa</h1>
            <ul>
                <li>Editar todos los datos de la unidad.</li>
                <li>Modificar choferes.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="eliminar_unidad">
            <h1>6_ Eliminar unidad</h1>
            <ul>
                <li>Desde edición de unidad → Borrar.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="obs">
            <h1>7_ Observaciones</h1>
            <ul>
                <li>Mensaje que aparece en COBRAR MÓVILES.</li>
                <li>Se puede crear, editar o borrar.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="voucher">
            <h1>8_ Voucher</h1>
            <ul>
                <li>Importación desde Excel.</li>
                <li>Filtrar, validar y borrar.</li>
                <li>Se usan al cobrar al móvil.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="voucher_manual">
            <h1>9_ Carga manual de voucher</h1>
            <ul>
                <li>Usado si no se puede exportar desde la app.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="productos">
            <h1>10_ Productos</h1>
            <ul>
                <li>Crear productos, stock y precio.</li>
                <li>Eliminar si es necesario.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="venta">
            <h1>11_ Venta de productos</h1>
            <ul>
                <li>Ingresar móvil → seleccionar productos → Guardar.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="tarifas">
            <h1>12_ Tarifas</h1>
            <ul>
                <li>Configurar semana, viaje y abonos.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="viajes">
            <h1>13_ Importe de viajes</h1>
            <ul>
                <li>Actualizar cuando aumentan.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="semanas">
            <h1>14_ Abonos semanales</h1>
            <ul>
                <li>Modificar valores cuando cambian tarifas.</li>
            </ul>
            <a href="#top">Volver al índice</a>
        </section>

        <section id="cobrar_moviles_tropas">
            <h1>15_ Cobrar a móviles y tropas</h1>
            <p>Se detallan deudas, viajes, semanas y movimientos asociados.</p>
            <a href="#top">Volver al índice</a>
        </section>

        <button class="volver" onclick="cerrarPagina()">Volver</button>

    </div>

    <script>
        function cerrarPagina() {
            window.close();
        }
    </script>

    <?php foot(); ?>

</body>

</html>