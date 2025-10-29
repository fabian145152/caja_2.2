<?php
include_once "../../funciones/funciones.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda Cobros</title>
    <?php head(); ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            color: #333;
            /*text-align: center;*/
        }

        ul {
            line-height: 1.6;
        }

        li {
            font-size: 15px;
        }

        button {
            display: inline-block;
            padding: 8px 16px;
            margin: 10px 5px 20px 0;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>

    <button onclick="cerrarPagina()">Cerrar Página</button>

    <div id="indice">
        <h2>Índice</h2>
        <h3>Botones del menu principal.</h3>
        <ul>
            <li><a href="#crear_usuarios">1_ Creacion de usuarios.</a></li>
            <li><a href="#licencia">2_ Licencia.</a></li>
            <li><a href="#crear_unidad">3_ Creacion de unidades.</a></li>
            <li><a href="#crear_tropa">4_ Tropas.</a></li>
            <li><a href="#edicion_uni_comp">5_ Edicion de unidad completa.</a></li>
            <li><a href="#eliminar_unidad">6_ Eliminar unidad.</a></li>
            <li><a href="#obs">7_ Observaciones.</a></li>
            <li><a href="#voucher">8_ Voucher.</a></li>
            <li><a href="#voucher_manual">9_ Cargar Voucher manualmente.</a></li>
            <li><a href="#productos">10_ Stock de productos.</a></li>
            <li><a href="#venta">11_ Venta de productos a las unidades.</a></li>
            <li><a href="#tarifas">12_ TARIFAS "Configurar undades para cobrar".</a></li>
            <li><a href="#viajes">13_ Importe de los viajes.</a></li>
            <li><a href="#semanas">14_ Abonos semanales.</a></li>
            <li><a href="#cobrar_moviles_tropas"></a>15_ Cobrar a moviles y tropas.</li>
            <li><a href="#Bonifica_semanas"></a>16_ Bonifica semanas</li>
            <li><a href="#"></a>17_</li>
            <li><a href="#"></a>18_</li>
            <li><a href="#"></a>19_</li>
            <li><a href="#"></a>20_</li>
            <li><a href="#"></a>21_</li>
            <li><a href="#"></a>22_</li>
            <li><a href="#"></a>23_</li>

        </ul>
    </div>
    <section id="crear_usuarios">
        <h1>1_Ayuda de creación de usuarios.</h1>

        <ul>
            <li>Esta sección crea los usuarios con su contraseña.</li>
            <ul>
                <li>Con el botón nuevo usuario entra a la sección <strong>NUEVO USUARIO.</strong></li>
                <li>Crear el usuario y la contraseña repitiendola.</li>
                <li>Cargar correo electronico.</li>
                <ul>
                    <h5>Tambien podra: </h5>
                    <li>Crear y edición de unidades.</li>
                    <li>Crear y edición de tropas.</li>
                    <li>Crear de productos para la venta.</li>
                    <li>Dar de baja unidades</li>
                    <li>Cambiar numero de movil.</li>
                    <li>Configurar unidades para cobrar.</li>
                    <li>Cobrarle a los titulares y a las tropas.</li>
                    <li>Trabajar con Voucher.</li>
                    <li>Vender productos a las unidades.</li>
                    <li>Cambiar las tarifas.</li>
                    <li>Crear y editar las deudas.</li>
                    <li>Actualizar semanas.</li>
                    <li>Crear y Editar saldo a favor.</li>
                    <li>Al eliminar al usuario, este no podrá ingresar más al sistema.</li>
                </ul>
            </ul>
        </ul>
        <a href="help_usuarios.php">INDICE</a>
        <section id="licencia">
            <h1>2_ Licencia</h1>
            <ul>
                <li>EL sistema tiene una licencia mensual.</li>
                <li>Comuniquese con el administrador del sistema.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="crear_unidad">
            <h1>3_ Creación de unidades nuevas.</h1>
            <ul>
                <li>Primeramente crear el numero de unidad, si esta repetida un alerta se lo informara.</li>
                <li>Luego presionar el botón CREAR / EDITAR TITULAR TROPA.</li>
                <li>Con CTRL + F buscar el numero creado anteriormente y presionar el botón actualizar.</li>
                <li>CAMPOS. </li>
                <ul>
                    <li>Movil no se puede editar</li>
                    <li>TROPA: si en un titular con una sola unidad cargarle el numero "50" indica que no es tropa</li>
                    <li>Nombre</li>
                    <li>Apellido</li>
                    <li>Direccion</li>
                    <li>Codigo Postal</li>
                    <li>Celular</li>
                    <li>Licencia</li>
                    <li>Estado administrativo</li>
                </ul>
                <li></li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="crear_tropa">
            <h1>4_ Listar torpas.</h1>
            <ul>
                <li>La unidad con el numero mas bajo es el titular de la tropa.</li>
                <li>Es donde se cargaran todas las deuda o saldos a favor de la tropa.</li>
                <li>Con el botón de talles se ven todos los datos de cada uno de los choferes</li>
                <li>Y en actualizar se pueden editar todos los datos de cad uno de los choferes de la unidad.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="edicion_uni_comps">
            <h1>5_ Edicion de unidad completa.</h1>
            <ul>
                <li>Se pueden cargar los datos de uno o los dos choferes</li>
                <li>Se pueden editar los datos de las unidades</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="eliminar_unidad">
            <h1>6_ Eliminar unidad</h1>
            <ul>
                <li>Presionar el botón edición de unidad completa</li>
                <li>del botón borrar se eliminan las unidades.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="obs">
            <h1>7_ Observaciones.</h1>
            <ul>
                <li>En este modulo se puede cargar un mensaje</li>
                <li>Es un recordatorio que aparecera en la pantalla cobrar a moviles.</li>
                <li>Tambien se puede editar o borrar.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="voucher">
            <h1>8_ Tratamiento de los Voucher</h1>
            <ul>
                <li>De la plataforma de la emision de viajes se extrae el listado de voucher.</li>
                <li>Se puede extraer por movil o por fecha</li>
                <li>Con el botón seleccionar archivo se sube al servidor la planilla excel con los viajes.</li>
                <li>Se importan con el siguiente botón.</li>
                <li>Aparece toda la lista de Voucher extraida del excel y subida a nuestra plataforma.</li>
                <li>Se pueden eliminar arbitratriamente con el botón borrar que esta al lado de cada uno de los voucher.</li>
                <li>Con el boton Buscar x se filtran por número de unidad</li>
                <li>El boton Limpiar tabla borra todos los voucher guardados.</li>
                <li>Ingresando el numero de movil que aparece en la lista de abajo, aparecen listados todos los voucher de la corespondiente unidad.</li>
                <li>Con el botonDetalles se pueden modificar los datos del voucher, una vez editados desapareceran de la lista, (estan validados)</li>
                <li>Con el boton Validar desaparecen de la lista.</li>
                <li>Con el boton borrar tambien se eliminan.</li>
                <li>Estos Voucher validados quedaran guardados en el sistema hasta acceder a cobrarle al movil o a la tropa</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="voucher_manual">
            <h1>9_ Carga de Voucher manualmente.</h1>
            <ul>
                <li>Con este boton se cargan los voucher manualmente.</li>
                <li>Si poralguna causa no se pudo cargar un voucher ó</li>
                <li>No se cuenta con una App conn la posibilidad de exportar Voucher, se cargan por esta entrada.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="productos">
            <h1>10_ Stock de productos.</h1>
            <ul>
                <li>En esta pagina se crean los productos para la venta.</li>
                <li>Guardar el producto.</li>
                <li>El stock.</li>
                <li>El precio.</li>
                <li>Tambien se pueden eliminar.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="venta">
            <h1>11_ Vanta de productos a las unidades.</h1>
            <ul>
                <li>Como primer paso ingrese el numero de la unidad a venderle algun producto.</li>
                <li>En la parte superor de la pagina le dara el nombre del chofer y el numero de la unidad.</li>
                <li>Sobre la columna derecha vera casillas para tildar.</li>
                <li>Seleccione la casilla deseada y tildela. (No mas de 5 porductos por vez)</li>
                <li>Con el boton guardar le carga el producto vendido a la unidad selaccionada</li>
                <li>El producto vendido se vera en la pantalla cobrar a moviles.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="tarifas">
            <h1>12_ TARIFAS "Configurar unidades para cobrarle"</h1>
            <ul>
                <li>Primeramente ingrese el numero de la unidad a configurar.</li>
                <li>Seleccione di paga semanas o no.</li>
                <li>En el caso de que NO page semanas igualmente asignarle un valor.</li>
                <li>Asignarle tambien lo que paga por viaje</li>
                <li>Cargar el inicio de facturacion.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>

        <section id="Viajes">
            <h1>12_ Imposte de los viajes.</h1>
            <ul>
                <li>Cambio del valor de viaje.</li>
                <li>Modificarlo cada vez que tengan un aumento de las tarifas.</li>
                <li>Puede crear uno nuevo o eliminar uno existente</li>
                <li>Puede guardar varios vlores de viajes distintos.</li>
                <li>no cargar viajes de importe "0" en configurar unodades para cobrar se selecciona si paga o no viajes.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>
        <section id="semanas">
            <h1>Importes semanales</h1>
            <ul>
                <li>Cambio del Abono semanal</li>
                <li>También modificarlo cada vez que tenga un aumento de tarifa.</li>
                <li>Puede crear uno nuevo o eliminar uno existente</li>
                <li>no cargar semanas de importe "0" en configurar unodades para cobrar se selecciona si paga o no semanas.</li>
                <li>Atencion: Cuando incremente el importe semanal. La pagina cobrar a moviles le va a dar un numero decimal.</li>
                <li>como cantidad de semanas adeudadas. Restarle importancia porque cuando pague la primer semana el numero se actualizara a entero.</li>
            </ul>
            <a href="help_usuarios.php">INDICE</a>
        </section>

        <button onclick="cerrarPagina()">Volver</button>
        <script>
            function cerrarPagina() {
                window.close();
            }
        </script>

        <br><br><br>

        <?php foot(); ?>
</body>

</html>