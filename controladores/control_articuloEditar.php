<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_articulo.php";
    require_once "../modelos/modelo_usuario.php";
    require_once "../modelos/modelo_foto.php";
    require_once "../modelos/modelo_etiqueta.php";
    require_once "../modelos/modelo_tiene_X.php";
    require_once "../modelos/modelo_comentario.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $articulo = new modelo_articulo;
    $fotos = new modelo_foto;
    $usuario = new modelo_usuario;
    $etiqueta = new modelo_etiqueta();
    $tiene_a = new modelo_tiene_X();
    $comentario = new modelo_comentario;

    $datosE = $etiqueta->listar_etiquetas();

    $divs_de_etiquetas = "";

    //Para cuando vamos a publicar un nuevo articulo
    if (isset($_GET["entrarPublicar"])) {

        if ($tipo_usuario!=-1 || $tipo_usuario!=3) {

            //Pintamos las etiquetas aqui para que funcione como en ENTRAR EDICION
            foreach ($datosE as $datoE) {
                $divs_de_etiquetas=$divs_de_etiquetas. '      
                <div class="form-check m-1">
                    <input class="form-check-input" type="checkbox" name="'.$datoE['id'].'" id="'.$datoE['id'].'" >
                    <label class="form-check-label" for="'.$datoE['id'].'">
                        '.$datoE['nombre'].'
                    </label>
                </div>';
            }

            include "../vistas/vista_articuloEditar.php";

        } else {
            header('Location: ./control_articulos.php');
        }

    //Para cuando vamos a editar uno nuevo
    } elseif (isset($_GET["entrarEdicion"])) {

        $datosC = $comentario->mostrar_comentarios_con_usuario(true, true, $_GET["entrarEdicion"], $id_usuario,6,1);
        $datos_a = $articulo->buscar_articulo_por_id($_GET["entrarEdicion"]);
        $etiquetasArticulo = $tiene_a->listar_etiquetas_de_lugar(true,$_GET["entrarEdicion"]);

        //Solo podra editar el articulo el creador, los moderadores y el administrador
        if ($datos_a["id_usuario"]==$id_usuario || $tipo_usuario==0 || $tipo_usuario==1) {

            $datos_f = $fotos->buscar_foto_por_articulo($datos_a["id"]);

            foreach ($datosE as $datoE) {
                $checked="";
                $validador = $tiene_a->exsiste_tiene_a(true,$_GET['entrarEdicion'],$datoE['id']);
                //compruebo que si la etiqueta que estamos pintando esta ya asignada aparezca la casilla como checked
                if (isset($validador['id_etiqueta'])) {
                    if ($validador['id_etiqueta']==$datoE['id']) {
                        $checked="checked";
                    }
                }

                $divs_de_etiquetas=$divs_de_etiquetas. '      
                <div class="form-check m-1">
                    <input class="form-check-input" type="checkbox" name="'.$datoE['id'].'" id="'.$datoE['id'].'" '.$checked.'>
                    <label class="form-check-label" for="'.$datoE['id'].'">'.$datoE['nombre'].'</label>
                </div>';
            }

            include "../vistas/vista_articuloEditar.php";

        } else {
            header('Location: ./control_articuloLeer.php?ver='.$datos_a["id"]);
        }

    //Cuando presionamos el boton de publicar
    } else if (isset($_POST["borrar"])) {

        $veces=0;
        for ($i=1; $i <= 6; $i++) {
            if(isset($_POST['borrarIDS'.strval($i)])) {
                $comentario->borrar_comentario (true, $_POST['borrarIDS'.strval($i)],$_POST['id_articulo'],  $_POST['fecha'.strval($i)]);
                $veces++;
            }
        }
        
        if ($veces!=0) {
            header('Location: ./control_articuloEditar.php?entrarEdicion='.$_POST['id_articulo'].'&bien');
        } else {
            header('Location: ./control_articuloEditar.php?entrarEdicion='.$_POST['id_articulo'].'&error=4');
        }
            


    } elseif (isset($_POST["publicar"])) {

        $titulo = trim($_POST['titulo']);
        $cuerpo = nl2br(trim($_POST['cuerpo']));
        $fecha=date('Y-m-d');

        if ($tipo_usuario==2) {
            $tipoArticulo = 0;
        } else {
            $tipoArticulo = 1;
        }

        $id_articulo_nuevo = $articulo->sacar_id_correspondiente_articulo();

        if ($titulo!="" and $cuerpo!="") {
            if ($_FILES['imagen']['size']!=0) {
                $tipo = strstr($_FILES['imagen']["type"], "image");
    
                //Comprobamos que el archivo subido es de tipo imagen
                if ($tipo !="") {
                    $nameOriginal = $_FILES['imagen']['name'];
    
                    //le damos nombre a la imagen y preparamos para subirla
                    //el nombre de las fotos de perfil siempre seran el id del usuario
                    $extension = strpbrk($nameOriginal, ".");
                    $portada = "portada".$id_articulo_nuevo.$extension;
    
                    $articulo->insertar_articulo($titulo,$cuerpo,$fecha,$id_usuario,$tipoArticulo,$portada);
    
                    //Creamos la carpeta par guardar todas las fotos del articulo
                    mkdir("../imagenes/fotos_articulos/".$id_articulo_nuevo."/");
                    
                    $temp = $_FILES['imagen']['tmp_name'];
                    $ruta = "../imagenes/fotos_articulos/".$id_articulo_nuevo."/".$portada;
                    move_uploaded_file ($temp,$ruta);
    
                    for ($i=0;$i<6;$i++) {
                        if ($_FILES['foto'.$i]['size']!=0) {
                            $tipo = strstr($_FILES['foto'.$i]["type"], "image");
    
                            //Comprobamos que el archivo subido es de tipo imagen
                            if ($tipo !="") {
                                $nameOriginal = $_FILES['foto'.$i]['name'];
    
                                //le damos nombre a la imagen y preparamos para subirla
                                //el nombre de las fotos de perfil siempre seran el id del usuario
                                $extension = strpbrk($nameOriginal, ".");
                                $foto = "foto".$i.$extension;
    
                                //Creamos la carpeta par guardar todas las fotos del articulo                            
                                $temp = $_FILES['foto'.$i]['tmp_name'];
                                $ruta = "../imagenes/fotos_articulos/".$id_articulo_nuevo."/".$foto;
                                
                                $fotos->insertar_foto($ruta,$id_articulo_nuevo);
                                move_uploaded_file ($temp,$ruta);
                            } else {
                                header('Location: ./control_articuloEditar.php?entrarPublicar&error=3');
                            }
                        }
                    }

                    $cantidad_etiquetas = $etiqueta->contar_etiquetas();
                    for ($i=1; $i <= $cantidad_etiquetas; $i++) {
                        if(isset($_POST[strval($i)])) {
                            $tiene_a->crear_tiene_x(true,$id_articulo_nuevo,$i);
                        }
                    }

                    header('Location: ./control_articuloLeer.php?ver='.$id_articulo_nuevo);
                //Si me sube una archivo que no sea una imagen devolvemos un error 
                } else {
                    header('Location: ./control_articuloEditar.php?entrarPublicar&error=3');
                }
            } else {
                header('Location: ./control_articuloEditar.php?entrarPublicar&error=2');
            }
        } else {
            header('Location: ./control_articuloEditar.php?entrarPublicar&error=1');

        }

    //Cuando presionamos el boton de editar
    } elseif (isset($_POST['edicion'])) {

        $titulo = trim($_POST['titulo']);
        $cuerpo = nl2br(trim($_POST['cuerpo']));

        $id_articulo = $_POST['id_articulo'];

        if ($titulo!="" and $cuerpo!="") {

            if ($_FILES['imagen']['size']!=0) {
                $tipo = strstr($_FILES['imagen']["type"], "image");
    
                //Comprobamos que el archivo subido es de tipo imagen
                if ($tipo !="") {
                    $nameOriginal = $_FILES['imagen']['name'];
    
                    //le damos nombre a la imagen y preparamos para subirla
                    //el nombre de las fotos de perfil siempre seran el id del usuario
                    $extension = strpbrk($nameOriginal, ".");
                    $portada = "portada".$id_articulo.$extension;
    
                    //Borramos la portada anterior
                    array_map('unlink', glob('../imagenes/fotos_articulos/'.$id_articulo.'/portada*'));

                    $temp = $_FILES['imagen']['tmp_name'];
                    $ruta = "../imagenes/fotos_articulos/".$id_articulo."/".$portada;
                    move_uploaded_file ($temp,$ruta);
    
    
                //Si me sube una archivo que no sea una imagen devolvemos un error 
                } else {
                    header('Location: ./control_articuloEditar.php?entrarEdicion='.$id_articulo.'&error=3');
                }
            } 
            $articulo->modificar_articulo($titulo,$cuerpo,$id_articulo);
            for ($i=0;$i<6;$i++) {
                if ($_FILES['foto'.$i]['size']!=0) {
                    $tipo = strstr($_FILES['foto'.$i]["type"], "image");
    
                    //Comprobamos que el archivo subido es de tipo imagen
                    if ($tipo !="") {
                        $nameOriginal = $_FILES['foto'.$i]['name'];
    
                        //le damos nombre a la imagen y preparamos para subirla
                        //el nombre de las fotos de perfil siempre seran el id del usuario
                        $extension = strpbrk($nameOriginal, ".");
                        $foto = "foto".$i.$extension;
    
                        //Creamos la carpeta par guardar todas las fotos del articulo                            
                        $temp = $_FILES['foto'.$i]['tmp_name'];
                        $ruta = "../imagenes/fotos_articulos/".$id_articulo."/".$foto;
                        
                        //Si la imagen solo ha sido editada 
                        //Borramos la anterior y la remplazamso
                        if(isset($_POST['exsiste'.$i])) {
                            array_map('unlink', glob('../imagenes/fotos_articulos/'.$id_articulo.'/foto'.$i));
                            move_uploaded_file ($temp,$ruta);
                        //Si ha añadido una nueva la insertamos también en la base de datos     
                        } else {
                            $fotos->insertar_foto($ruta,$id_articulo);
                            move_uploaded_file ($temp,$ruta);
                        }

                    } else {
                        header('Location: ./control_articuloEditar.php?entrarPublicar&error=3');
                    }
                }
            }

            $cantidad_etiquetas = $etiqueta->contar_etiquetas();
            $etiquetasArticulo = $tiene_a->listar_etiquetas_de_lugar(true,$id_articulo);

            //Limpiamos todas las etiquetas asociadas a este articulo
            foreach($etiquetasArticulo as $etiquetaArticulo) {
                $tiene_a->borrar_tiene_x(true,$id_articulo,$etiquetaArticulo['id_etiqueta']);
            }

            for ($i=1; $i <= $cantidad_etiquetas; $i++) {
                if(isset($_POST[strval($i)])) {
                    $tiene_a->crear_tiene_x(true,$id_articulo,$i);
                }
            }

            header('Location: ./control_articuloLeer.php?ver='.$id_articulo);

        } else {
            header('Location: ./control_articuloEditar.php?entrarEdicion='.$id_articulo.'&error=1');
        }

    } else {
        header('Location: ./control_articulos.php');
    }

?>