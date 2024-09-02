<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_articulo.php";
    require_once "../modelos/modelo_usuario.php";
    require_once "../modelos/modelo_comentario.php";
    require_once "../modelos/modelo_foto.php";

    $fecha=date('Y-m-d');

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $articulo = new modelo_articulo;
    $foto = new modelo_foto;
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
        $offset = ($pagina_actual-1)*3;

        $datosA = $articulo->buscar_articulo_por_id($_GET['ver']);
        $datosF = $foto->buscar_foto_por_articulo($_GET['ver']);
        $datosC = $comentario->mostrar_comentarios_con_usuario(true, false, $_GET['ver'], $id_usuario,3,$offset);

        $datosRecomendados = $articulo->mostrar_ultimos_3_articulos($_GET['ver']);
        $datosAutor = $usuario->buscar_usuario_por_id($datosA["id_usuario"]);

        $exsiste_favorito = $articulo->comprobar_favorito($_GET['ver'],$id_usuario);

        $existe_comentario = $comentario->comprobar_comentario_exsiste(true,$_GET['ver'], $id_usuario, $fecha);
        //Creamos unos json para el paginado de los comentarios 
        crearJSON($datosC, 'comentarios',false);

        $comentarios_totales = $comentario->mostrar_comentarios(true,$_GET['ver']);
        $num_comentarios = count($comentarios_totales);
        $num_paginas = $num_comentarios/3;

        //Este otro json tendra el Id y el tipo del usuario que este registrado
        //Lo creamos para poder mostrar el boton de borrar comentario acorde a cada usuario
        crearJSON ($array_usuario, 'DatosUsuario',false);

            
    }  elseif (isset($_POST['borrar_comentario'])) {

        $comentario->borrar_comentario(true, $_POST['id_usuario'], $_POST['id_tabla'], $_POST['fecha']);

        header('Location: ./control_articuloLeer.php?ver='.$_POST['id_tabla'].'#comentarios');

    } elseif (isset($_POST['escribir_comentario'])) {
        $cuerpo = trim($_POST['cuerpo']);
        if ($cuerpo!="") {
            $comentario->insertar_comentario(true,$_POST['id_usuario'],$_POST['id_tabla'], $fecha, $cuerpo);
            header('Location: ./control_articuloLeer.php?ver='.$_POST['id_tabla'].'#comentarios');
        } else {
            header('Location: ./control_articuloLeer.php?ver='.$_POST['id_tabla'].'&error=1#comentarios');
        }

    } elseif (isset($_GET['borrarFav'])) {


        $articulo->borrar_de_favorito($_GET['borrarFav'], $id_usuario);
        header('Location: ./control_articuloLeer.php?ver='.$_GET['borrarFav']);

    } elseif (isset($_GET['añadirFav'])) {

        $articulo->añadir_a_favorito ($_GET['añadirFav'], $id_usuario);
        header('Location: ./control_articuloLeer.php?ver='.$_GET['añadirFav']);

    } else {
        header('Location: ../index.php');
    }


    include "../vistas/vista_articuloLeer.php";

?>