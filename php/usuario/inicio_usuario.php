<?php
session_start();
if ($_SESSION['logueado']) {

    echo "BIENVENIDO ,"  . $_SESSION['uname'] . '<br>';

    echo "Hora de conexión :" . $_SESSION['time'] . '<br><br>';

    include_once("../../funciones/funciones.php");

    $con = conexion();

    $con->set_charset("utf8mb4");
    $contador = 0;
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>USUARIOS</title>
        <?php head(); ?>
    </head>

    <body>
        <?php
        $sql = "SELECT * FROM users WHERE 1";
        $listar = $con->query($sql);
        ?>
        <div class="text-center">
            <a href="nuevo_usuario.php" class="btn btn-primary btn-sm">NUEVO USUARIO</a>
        </div>

        <br>

        <br>
        <table class="table table-bordered table-sm table-hover">
            <thead class="thead-dark">
                <tr>

                    <th>Nombre de usuario</th>
                    <th>Password</th>
                    <th>email</th>
                    <th>Fecha de creacion</th>
                    <th>Creado por</th>
                    <th>Nivel</th>
                    <th></th>
                </tr>
            </thead>

            <div>
                <thead>
                    <?php

                    while ($ver = $listar->fetch_assoc()) {
                        $contador++; // Omitir el primer registro 
                        if ($contador == 1) {
                            continue;
                        }
                    ?>
                        <form action="delete_usuario.php" method="get">
                            <tr>
                                <?php $id = $ver['id_users'] ?>
                                <th><?php echo $ver['username'] ?></th>
                                <th><?php echo "*******" ?></th>
                                <th><?php echo $ver['email'] ?></th>
                                <th><?php echo $ver['initial_date'] ?></th>
                                <th><?php echo $ver['uname'] ?></th>
                                <th><?php echo $ver['permiso'] ?></th>
                                <th><button type="submit" name="qq" id="qq" value="<?php echo $id ?>" class=" btn btn-danger btn-sm">BORRAR</button></th>
                            </tr>

                        </form>
                    <?php
                    }
                    ?>
                </thead>
            </div>
        </table>
        <div class="text-center">
            <button onclick="window.close()" class="btn btn-danger btn-sm">Salir</button>
        </div>

        <?php foot();
        ?>

    </body>

    </html>
<?php
}
?>