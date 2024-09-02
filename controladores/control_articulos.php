<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_articulo.php";
    require_once "../modelos/modelo_etiqueta.php";
    
    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $articulo = new modelo_articulo();
    $etiqueta = new modelo_etiqueta();
    $id = false;

    $datosE = $etiqueta->listar_etiquetas();

    //Aqui calculamos la pagina actual 
    if (isset($_POST['atras'])) {
        $pagina_actual=$_POST['pagina_actual']-1;
    } elseif (isset($_POST['delante'])) {
        $pagina_actual=$_POST['pagina_actual']+1;
    } else {
        $pagina_actual=1;
    }

    //Esta es la formula matematica para el paginado
    $offset = ($pagina_actual-1)*6;
    $Tipo = 1;

    //Funcion de busqueda
    if (isset($_POST['busqueda'])) {

        if(isset($_POST['id'])) {
            $id_busqeda=$_POST['id'];
        } else {
            $id_busqeda=="";
        }

        
        if ($id_busqeda!="") {
            $datosA = $articulo->buscar_articulo_por_id($id_busqeda);
            $id = true;
            $Tipo=$datosA['tipo'];

        } else {
            $id = false;
            $cantidad_etiquetas = $etiqueta->contar_etiquetas();

            if (isset($_POST['etiquetas_paginado'])) {
                $parte_de_etiquetas = $_POST['etiquetas_paginado'];
            } else {
                $parte_de_etiquetas = "";
            }
            $primero=true;
            for ($i=1; $i <= $cantidad_etiquetas; $i++) {
                if(isset($_POST[strval($i)])) {
                    if($primero) {
                        $parte_de_etiquetas = $i;
                        $primero=false;
                    } else {
                        $parte_de_etiquetas = $parte_de_etiquetas." and ".$i;
                    }
                }
            }

            $Tipo = $_POST['tipo'];
            $Titulo = $_POST['titulo'];

            //Si ha seleccionado alguna etiqueta llamamos a la consulta que busca tambien por etiquetas
            if ($parte_de_etiquetas!="") {
                $datosA = $articulo->buscar_articulo_por_titulo_tipo_y_etiquetas ($Titulo, $Tipo,$offset,$parte_de_etiquetas);
            } else {
                $datosA = $articulo->buscar_articulo_por_titulo_y_tipo ($Titulo, $Tipo,$offset);
            }
        }


    } else {
        $datosA = $articulo->buscar_articulo_por_titulo_y_tipo("",$Tipo,$offset);
    }

    //Funcion para borrar articulos selecionados
    if (isset($_POST['borrar'])) {
        $veces=0;
        for ($i=1; $i <= 6; $i++) {
            if(isset($_POST['borrarIDS'.strval($i)])) {
                $articulo->borrar_articulo($_POST['borrarIDS'.strval($i)]);
                array_map('unlink', glob('../imagenes/fotos_articulos/'.$_POST['borrarIDS'.strval($i)].'/*'));
                rmdir('../imagenes/fotos_articulos/'.$_POST['borrarIDS'.strval($i)]);
                $veces++;
            }
        }     
        
        if ($veces!=0) {
            header('Location: ./control_articulos.php?bien');
        } else {
            header('Location: ./control_articulos.php?error');
        }
    }

    if ($Tipo == 1) {
        $TipoNombre="Oficiales";
    } else {
        $TipoNombre="Comunitarios";
    }

    //Aqui calculamos el numero de páginas que nos saldran
    $num_articulos = count($datosA);
    $num_paginas = $num_articulos/6;

    //Esto siempre al final
    crearJSON($datosE, 'etiqueta',false);
    crearJSON($datosA, 'articulo', false);
    include "../vistas/vista_articulos.php";


?>
