<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_evento.php";
    
    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $fecha=date('Y-m-d');
    $evento = new modelo_evento();

    //Aqui calculamos la pagina actual 
    if (isset($_POST['atras'])) {
        $pagina_actual=$_POST['pagina_actual']-1;
    } elseif (isset($_POST['delante'])) {
        $pagina_actual=$_POST['pagina_actual']+1;
    } else {
        $pagina_actual=1;
    }

    $id = false;

    //Esta es la formula matematica para el paginado
    $offset = ($pagina_actual-1)*3;

    //Funcion de busqueda
    if (isset($_POST['busqueda'])) {

        
        if(isset($_POST['id'])) {
            $id_busqeda=$_POST['id'];
        } else {
            $id_busqeda=="";
        }

        if ($id_busqeda!="") {
            $datosE = $evento->buscar_evento_por_id($id_busqeda);
            $id = true;
            $Titulo = "";
        } else {
            $Titulo = "";
            $partesTipo = "";
            $parte_fechas = "";
    
            if (isset($_POST['titulo'])) {
                $Titulo = $_POST['titulo'];
            }
    
            if (isset($_POST['tipo'])) {
                $partesTipo = 'tipo='.$_POST['tipo'].' and ';
            }
    
            if ($_POST['dateFrom']!=""){
                $dateFrom = $_POST['dateFrom'];
                $parte_fechas = 'fecha>='.$dateFrom.' and ';
                if ($_POST['dateTo']!=""){
                    $dateTo = $_POST['dateTo'];
                    $parte_fechas = 'fecha BETWEEN "'.$dateFrom.'" and "'.$dateTo.'" and ';
                }
    
            }
            $datosE = $evento->buscar_evento_por_titulo ($Titulo,$partesTipo,$offset,$parte_fechas);
        }
        
    } else if (isset($_POST["borrar"])) {

        $veces=0;
        for ($i=1; $i <= 3; $i++) {
            if(isset($_POST['borrarIDS'.strval($i)])) {
                $evento->borrar_evento($_POST['borrarIDS'.strval($i)]);
                array_map('unlink', glob('../imagenes/fotos_eventos/'.$_POST['borrarIDS'.strval($i)].'*'));
                $veces++;
            }
        }
        
        if ($veces!=0) {
            header('Location: ./control_eventos.php?bien');
        } else {
            header('Location: ./control_eventos.php?&error=4');
        }
            
    } else {
        $datosE = $evento->buscar_evento_por_titulo("","",$offset, "");
    }

    //Funciones de editar para admin (puede que las ponga en otro controlador)

    //Aqui calculamos el numero de páginas que nos saldran
    $num_articulos = count($datosE);
    $num_paginas = $num_articulos/3;

    include "../vistas/vista_eventos.php";


?>