<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_discusion.php";
    require_once "../modelos/modelo_etiqueta.php";
    
    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $discusion = new modelo_discusion();
    $etiqueta = new modelo_etiqueta();

    $datosE = $etiqueta->listar_etiquetas();

    //Aqui calculamos la página actual 
    if (isset($_POST['atras'])) {
        $pagina_actual=$_POST['pagina_actual']-1;
    } elseif (isset($_POST['delante'])) {
        $pagina_actual=$_POST['pagina_actual']+1;
    } else {
        $pagina_actual=1;
    }

    $id = false;

    //Esta es la formula matematica para el paginado
    $offset = ($pagina_actual-1)*6;

    //Funcion de busqueda
    if (isset($_POST['busqueda'])) {


        if(isset($_POST['id'])) {
            $id_busqeda=$_POST['id'];
        } else {
            $id_busqeda=="";
        }

        if ($id_busqeda!="") {
            $datosA = $articulo->buscar_discusion_por_id($id_busqeda);
            $id = true;
        } else {
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
            $Titulo = $_POST['titulo'];

            //Si ha seleccionado alguna etiqueta llamamos a la consulta que busca tambien por etiquetas
            if ($parte_de_etiquetas!="") {
                $datosA = $discusion->buscar_discusion_por_titulo_tipo_y_etiquetas ($Titulo,$offset,$parte_de_etiquetas);
            } else {
                $datosA = $discusion->buscar_discusion_por_titulo ($Titulo,$offset);
            }
        }

    } else {
        $datosA = $discusion->buscar_discusion_por_titulo("",$offset);
    }

    if (isset($_POST['borrar'])) {
        $veces=0;
        for ($i=1; $i <= 6; $i++) {
            if(isset($_POST['borrarIDS'.strval($i)])) {
                $discusion->borrar_discusion($_POST['borrarIDS'.strval($i)]);
                $veces++;
            }
        }     
        
        if ($veces!=0) {
            header('Location: ./control_discusiones.php?bien');
        } else {
            header('Location: ./control_discusiones.php?error');
        }
    }

    //Funciones de editar para admin (puede que las ponga en otro controlador)

    //Aqui calculamos el numero de páginas que nos saldran
    $num_articulos = count($datosA);
    $num_paginas = $num_articulos/6;
    //Esto siempre al final
    crearJSON($datosA, 'discusion',false);
    include "../vistas/vista_discusiones.php";

?>
