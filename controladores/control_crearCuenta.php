<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_usuario.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $usuario = new modelo_usuario;

    if (isset($_POST['enviar'])) {

        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $nick = trim($_POST['nick']);
        $pass = trim($_POST['pass']);

        if ($nombre!="" && $email!="" && $nick !="" && $pass!="") {
            $pass = md5(md5(md5($pass)));
            $usuario -> insertar_usuario ($nombre,$email,$nick,$pass);
            header('Location:../vistas/vista_login.php');
        } else {
            header('Location:../vistas/vista_crearCuenta.php');
        }

    } else {
        header('Location: ../index.php');
    }


?>