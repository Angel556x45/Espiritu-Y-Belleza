<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_usuario.php";
    
    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $fecha=date('Y-m-d');
    $usuario = new modelo_usuario();

    if ($tipo_usuario > 1) {
        header('Location: ../index.php');
    }

    if (isset($_GET['ver'])) {
        
        $datosU = $usuario->buscar_usuario_por_id($_GET['ver']);
        include "../vistas/vista_usuario_ver.php";

    } else if (isset($_POST['suspender'])) {

        $usuario->cambiar_estado_del_usuario($_POST['suspender'], 0);
        header('Location: ./control_usuario_ver.php?ver='.$_POST['suspender']);

    } else if (isset($_POST['activar'])) {

        $usuario->cambiar_estado_del_usuario($_POST['activar'], 1);
        header('Location: ./control_usuario_ver.php?ver='.$_POST['activar']);

    } else if (isset($_POST['cambiar_rango'])) {

        $usuario->cambiar_tipo_del_usuario($_POST['cambiar_rango'], $_POST['tipo']);
        header('Location: ./control_usuario_ver.php?ver='.$_POST['cambiar_rango']);
    }

?>