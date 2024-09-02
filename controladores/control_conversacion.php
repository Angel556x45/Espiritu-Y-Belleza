<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_mensaje.php";
    require_once "../modelos/modelo_usuario.php";

    $fecha=date('Y-m-d');

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $mensaje = new modelo_mensaje;
    $usuario = new modelo_usuario;

    if (isset($_GET['usuarioOTRO'])) {

        $mensaje->marcar_leidos($_GET['usuarioOTRO'],$id_usuario);
        $datosM = $mensaje->mostrar_conversacion($_GET['usuarioOTRO'],$id_usuario);
        $datosU = $usuario->buscar_usuario_por_id($_GET['usuarioOTRO']);

        if (count($datosM)==0) {
            $ContainerClass = "container-fluid p-5 conversacion-vacia";
        } else {
            $ContainerClass = "container-fluid p-5";
        }

    } else {
        header('Location: ../index.php');
    }

    include "../vistas/vista_conversacion.php";

?>