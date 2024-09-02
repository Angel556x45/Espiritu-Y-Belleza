<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_discusion.php";
    require_once "../modelos/modelo_usuario.php";
    require_once "../modelos/modelo_comentario.php";

    $fecha=date('Y-m-d H:i:s');

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $discusion = new modelo_discusion;
    $comentario = new modelo_comentario;
    $usuario = new modelo_usuario;

    if (isset($_GET['ver'])) {

        if (isset($_GET['atras'])) {
            $pagina_actual=$_GET['pagina_actual']-1;
        } elseif (isset($_GET['delante'])) {
            $pagina_actual=$_GET['pagina_actual']+1;
        } else {
            $pagina_actual=1;
        }
        $offset = ($pagina_actual-1)*6;

        $datosD = $discusion->buscar_discusion_por_id($_GET['ver']);
        $datosC = $comentario->mostrar_comentarios_con_usuario(false, false, $_GET['ver'], $id_usuario,6,$offset);

        $datosAutor = $usuario->buscar_usuario_por_id($datosD["id_usuario"]);

        //Creamos unos json para el paginado de los comentarios 
        crearJSON($datosC, 'comentarios',false);

        $comentarios_totales = $comentario->mostrar_comentarios(false,$_GET['ver']);
        $num_comentarios = count($comentarios_totales);
        $num_paginas = $num_comentarios/6;

        //Este otro json tendra el Id y el tipo del usuario que este registrado
        //Lo creamos para poder mostrar el boton de borrar comentario acorde a cada usuario
        crearJSON ($array_usuario, 'DatosUsuario',false);
    }  elseif (isset($_POST['borrar_comentario'])) {
        $comentario->borrar_comentario(false, $_POST['id_usuario'], $_POST['id_tabla'], $_POST['fecha']);
        header('Location: ./control_discusionLeer.php?ver='.$_POST['id_tabla'].'#comentarios');
    } elseif (isset($_POST['escribir_comentario'])) {
        $comentario->insertar_comentario(false,$_POST['id_usuario'],$_POST['id_tabla'], $fecha, $_POST['cuerpo']);
        header('Location: ./control_discusionLeer.php?ver='.$_POST['id_tabla'].'#comentarios');

    } else {
        header('Location: ../index.php');
    }

    include "../vistas/vista_discusionLeer.php";

 ?>