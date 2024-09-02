<?php 

    require_once "../funciones.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    pintar_menu(false,$tipo_usuario, $id_usuario);

    cerrar_sesion(true);

    if ($id_usuario != -1) {
      header('Location: ../index.php');
    }

?>

        <main>
          <section class="container-lg">

            <div class="row d-flex justify-content-center">

              <div class="col-md-5">

                <form action="../controladores/control_login.php" method="POST">

                  <div class="text-center">
                    <label for="username" class="form-label mt-4"><h4>Nick de usuario</h4></label>
                    <input type="text" class="form-control" name='nick' id="username" placeholder="Escriba su nick de usuario">
                  </div>
  
                  <div class="text-center">
                    <label for="password" class="form-label mt-4"><h4>Contraseña</h4></label>
                    <input type="password" class="form-control" name='pass' id="password" placeholder="Escriba su contraseña" autocomplete="off">
                    <small class="form-text text-muted">No compartas tu contraseña</small>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="mantener">
                    <label class="form-check-label" for="flexCheckDefault">
                      Mantener sesión 
                    </label>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-outline-info" name="enviar">Iniciar sesión</button>
                  </div>

                  <div class="text-center mt-5">
                    <h4>¿No tienes cuenta?</h4>
                    <a href="./vista_crearCuenta.php" class="btn btn-success"> CREAR CUENTA </a>
                  </div>
  
                </form>

              </div>

            </div>

          </section>

          <?php 
            if (isset ($_GET['error'])) {

              echo '<section class="container">';
              switch($_GET['error']) {
                  case 1:
                      echo '    
                      <div class="alert alert-dismissible alert-warning text-center">
                          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          <p> <strong>Error</strong> No has puesto bien el Nick o la Contraseña </p>
                      </div>';
                      break;  
              }
              echo '</section>';
            }      
        ?>
          
<?php 

  pintar_footer(false);

?> 