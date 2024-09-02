<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_mensaje.php";

    $fecha=date('Y-m-d H:i:s');

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $mensaje = new modelo_mensaje;

    if (isset($_GET['entrar'])) {

        $url_iframe='./control_conversacion.php?usuarioOTRO='.$_GET['entrar'];


    } elseif (isset($_GET['escribir_mensaje'])) {

        $cuerpo = trim($_GET['cuerpo']);
        $usuario_recibe = $_GET['usuario_recibe'];

        if ($cuerpo!="") {
            $mensaje->escribir_mensaje($cuerpo, $fecha,"0",$id_usuario,$usuario_recibe);
        }
        header('Location: ./control_mensajesLeer.php?entrar='.$_GET['usuario_recibe']);

    } else {
        header('Location: ../index.php');
    }

    include "../vistas/vista_mensajesLeer.php";

?>