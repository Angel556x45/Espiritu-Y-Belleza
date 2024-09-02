<?php 
    require_once "../bd/bd.php";
    require_once "../modelos/modelo_usuario.php";

    session_start();

    if (isset($_COOKIE['sesion'])) {

        session_decode($_COOKIE['sesion']);

    }

    if (isset($_SESSION['id_usuario'])) {

        header('Location: ../index.php');
        
    } else {
        
        $usuario = new modelo_usuario();

        $datos = $usuario->login($_POST['nick'], $_POST['pass']);

        if ($datos["id"]==-1) {
            header('Location: ../vistas/vista_login.php?error=1');
        } else {
            if (isset($_POST["mantener"])) {
                $_SESSION['id_usuario'] = $datos["id"];

                $_SESSION['tipo_usuario'] = $datos['tipo'];

                $datoSesion = session_encode();

                setcookie ('sesion', $datoSesion, time()+10000000, "/");

                header('Location: ../index.php');

            } else {
                $_SESSION['id_usuario'] = $datos["id"];

                $_SESSION['tipo_usuario'] = $datos['tipo'];

                header('Location: ../index.php');
            }
        }

    }

?>