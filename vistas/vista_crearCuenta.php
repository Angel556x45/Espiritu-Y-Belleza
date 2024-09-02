<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_usuario.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    pintar_menu(false,$tipo_usuario, $id_usuario);

    $usuario = new modelo_usuario;

    $datosUsuarios = $usuario->listar_usuarios();

    crearJSON($datosUsuarios, 'todos_los_usuarios',false);


    cerrar_sesion(true);

    if ($id_usuario != -1) {
      header('Location: ../index.php');
    }

?>

        <main>

          <section class="container-lg">

            <div class="row d-flex justify-content-center">

              <div class="col-md-5">

                <form action="../controladores/control_crearCuenta.php" method="POST">

                  <div class="text-center">
                    <label for="nickname" class="form-label mt-4"><h4>Nick de usuario</h4></label>
                    <input type="text" class="form-control" name='nick' id="nickname" placeholder="Tu nick tiene que ser único" required>
                    <small id="nickHelp" class="form-text text-muted">Este nick sera el que uses para acceder a tu cuenta</small>
                  </div>

                  <div class="text-center">
                    <label for="email" class="form-label mt-4"><h4>Correo electronico</h4></label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Escribe un email válido" required>
                    <small id="emailHelp" class="form-text text-muted">Tranquilo, no compartiremos tu email con nadie.</small>
                  </div>

                  <div class="text-center">
                    <label for="nombre" class="form-label mt-4"><h4>Tu nombre</h4></label>
                    <input type="text" class="form-control" name='nombre' id="nombre" placeholder="Pon tu nombre" required>
                    <small class="form-text text-muted">Con este nombre te verán los demás usuarios</small>
                  </div>
  
                  <div class="text-center">
                    <label for="password" class="form-label mt-4"><h4>Contraseña</h4></label>
                    <input type="password" class="form-control" name='pass' id="password" placeholder="Escribe una contraseña" autocomplete="off" required>
                  </div>

                  <div class="text-center">
                    <label for="passwordConfirm" class="form-label mt-4"><h4>Confirmar Contraseña</h4></label>
                    <input type="password" class="form-control" name='passConfirm' id="passwordConfirm" placeholder="Confrima la contraseña" autocomplete="off" required>
                    <small id="passHelp" class="form-text text-muted">Tiene que ser la misma de antes</small>
                  </div>

                  <div class="text-center mt-3">
                    <button type="submit" class="btn btn-outline-success" name="enviar" id="send">CREAR CUENTA</button>
                  </div>

                </form>

              </div>

            </div>

          </section>
          
<?php 

  pintar_footer(false);

?> 