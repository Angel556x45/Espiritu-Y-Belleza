<?php 

    //INCLUIDO REQUIRE DEBAJO DEL CONTROLADOR
    //LAS VARIABLES DE LOS DATOS DEL USUARIO ESTAN EN EL CONTROLADOR

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);

    if ($id_usuario == -1) {
      header('Location: ../index.php');
    }

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

    <section class="container-lg border-top border-bottom border-dark mt-3">

        <div class="row mb-3 mt-3">

            <div class="col d-flex justify-content-center">

            <div class="col-md-7 m-1">

                <h3>USUARIO: <?php echo $datosU['nombre']; ?>  <span class="badge <?php echo $tipo[1] ?>"> <?php echo $tipo[0] ?> </span> </h3> 

                <p>Email: <?php echo $datosU['email']; ?></p>
                
                <p>Nick: <?php echo $datosU['nick']; ?></p>

                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#password" aria-controls="offcanvasExample">
                Cambiar contraseña
                </button>

                <div class="offcanvas offcanvas-start" tabindex="-1" id="password" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Cambiar contraseña</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <form action="./control_mi_perfil.php" method="POST">

                            <div>
                                <label for="password" class="form-label mt-2">Contraseña antigua</label>
                                <input type="password" class="form-control" name='pass_old' id="password" placeholder="..." autocomplete="off">
                            </div>

                            <div>
                                <label for="password" class="form-label mt-2">Contraseña nueva</label>
                                <input type="password" class="form-control" name='pass_new' id="password" placeholder="..." autocomplete="off">
                                <small class="form-text text-muted">Comprueba bien tu contraseña</small>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-outline-info" name="cambiar_pass">Cambiar contraseña</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

            <div class="col-md-2 m-1 text-center">
                <img src="../imagenes/foto_usuario/<?php echo $datosU['foto']; ?>" alt="foto_perfil" class="img-fluid foto_perfil">

                <div class="text-center">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#imagen">
                        <i class="fa fa-edit" style="font-size:36px"></i>
                    </button>
                </div>

                <div class="modal fade" id="imagen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
            
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"> NUEVA FOTO DE PERFIL </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="./control_mi_perfil.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <input class="form-control" name='imagen' type="file" id="formFile" required>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-outline-info" name="cambiar_imagen">Guardar cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>

            </div>
        </div>

    </section>

    <section class="container-md mt-5">

        <?php 
            if (isset($datosFavoritos[0])) {
                echo '
                <div class="row d-flex justify-content-center">
                    <div class="col-md-10 text-center">
                        <h2>Artículos Favoritos</h2>
                    </div>
                </div>
                ';
            }
        ?>

        <div class="row d-flex justify-content-center">
        <?php 
            foreach ($datosFavoritos as $datoF) {

                echo ' 
                <div class="col-md-3">

                    <div class="card mb-3">
                        <h3 class="card-header header-eventos text-center d-flex align-items-center justify-content-center">'. $datoF["titulo"] .'</h3>

                        <div class="foto_card" style="background-image: url(../imagenes/fotos_articulos/'.$datoF["id"] .'/'. $datoF["portada"].')">
                        </div>

                        <div class="card-body text-center">
                            <form action="control_articuloLeer.php" method="GET">
                            <button type="submit" name="ver" value=" '.$datoF["id"].' " class="btn btn-outline-info"> LEER </button>
                            </form>
                        </div>
                    </div>

                </div>
                ';

            }
            
        ?>
        </div>
    </section>

<?php 

  pintar_footer(false);

?> 