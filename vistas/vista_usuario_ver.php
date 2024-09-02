<?php 

    pintar_menu(false,$tipo_usuario, $id_usuario);
    cerrar_sesion(false);
    $tipo = nombre_tipos_de_usuario($datosU['tipo']);

?>

    <main>

        <?php 
        
            if (isset ($_GET['error'])) {

                echo '<section class="container">';
                switch($_GET['error']) {
                    case 1:
                        echo '    
                        <div class="alert alert-dismissible alert-danger text-center">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Error!</strong> Comprueba bien que has puesto tu <strong> contraseña antigua  </strong> 
                        </div>';
                        break;
                    case 2:
                        echo '    
                        <div class="alert alert-dismissible alert-danger text-center">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Error!</strong> no puedes subir un archivo <strong> vacio </strong> 
                        </div>';
                        break;
                    case 3:
                        echo '    
                        <div class="alert alert-dismissible alert-danger text-center">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Error!</strong> formato incorrecto 
                        </div>';
                        break;    
                }
                echo '</section>';
            }

            if (isset ($_GET['bien'])) {
                echo '<section class="container">';
                switch ($_GET['bien']) {
                    case 1:
                        echo '
                        <div class="alert alert-dismissible alert-success text-center">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Todo Ok!</strong> Tu contraseña se ha  <strong> cambiado  </strong> 
                        </div>
                        ';
                        break;
                    case 2:
                        echo '
                        <div class="alert alert-dismissible alert-success text-center">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Todo Ok!</strong> Tu foto de perfil <strong> ha sido actualizada </strong> 
                        </div>
                        ';
                        break;    
                }
                echo '</section>';
            }
        
        ?>

    <section class="container-fluid p-5 usuario-front d-flex justify-content-center align-items-center p-5">

        <div class="row text-center d-flex justify-content-center">
            <h1 class="texto-head-oscuro">EDITAR PERFIL</h2>
        </div>
    
    </section>

    <section class="container-lg mt-3">

        <div class="row d-flex justify-content-center">
         <form action="./control_usuario_ver.php" method="POST" class="col-md-12 text-center">
            <?php 
                if ($datosU['estado']==1) {
                    echo '
                        <button type="submit" class="btn btn-danger" name="suspender" value="'.$datosU['id'].'">Suspender cuenta</button>
                    ';
                } else {
                    echo '
                        <button type="submit" class="btn btn-success" name="activar" value="'.$datosU['id'].'">Reactivar cuenta</button>
                    ';
                }
            ?>
         </form>
        </div>


        <div class="row mb-3 mt-3 border-top border-bottom border-dark">

            <div class="col d-flex justify-content-center">

            <div class="col-md-7 m-1">

                <h3>USUARIO: <?php echo $datosU['nombre']; ?>  <span class="badge <?php echo $tipo[1] ?>"> <?php echo $tipo[0] ?> </span> </h3> 

                <p>Email: <?php echo $datosU['email']; ?></p>
                
                <p>Nick: <?php echo $datosU['nick']; ?></p>

                <div class="col-md-4">
                    <form action="./control_usuario_ver.php" method="POST">
                        <label for="exampleSelect1" class="form-label">Cambiar rango</label>
                        <select class="form-select" id="exampleSelect1" name="tipo">
                            <option value="1">Moderador</option>
                            <option value="2">Predicador</option>
                            <option value="3">Laico</option>
                        </select>
                        <button type="submit" class="btn btn-info" name="cambiar_rango" value="<?php echo $datosU['id']; ?>">Cambiar rango</button>
                    </form>
                </div>

            </div>

            <div class="col-md-2 m-1 text-center">
                <img src="../imagenes/foto_usuario/<?php echo $datosU['foto']; ?>" alt="foto_perfil" class="img-fluid foto_perfil">
            </div>

            </div>
        </div>

    </section>
<?php 

  pintar_footer(false);

?> 