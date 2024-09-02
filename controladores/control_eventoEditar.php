<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_evento.php";
    require_once "../modelos/modelo_asiste.php";

    $array_usuario=comprueba_usuario();

    $fechaAhora=date('Y-m-d');

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $evento = new modelo_evento;
    $asiste = new modelo_asiste;

    if ($tipo_usuario!=0 && $tipo_usuario!=1) {
        header('Location: ../index.php');
    }

    //Para cuando vamos a publicar un nuevo articulo
    if (isset($_GET["entrarPublicar"])) {

        if ($tipo_usuario!=-1 || $tipo_usuario!=2 || $tipo_usuario!=3) {

            include "../vistas/vista_eventoEditar.php";

        } else {
            header("Location: ./control_eventos.php");
        }

    //Para cuando vamos a editar uno nuevo
    } elseif(isset($_GET["ver"])) {

        $datos_e = $evento->buscar_evento_por_id($_GET['ver']);
        $datos_a = $asiste->mostrar_asistentes($_GET['ver']);
        if ($tipo_usuario!=-1 || $tipo_usuario!=2 || $tipo_usuario!=3) {

            include "../vistas/vista_eventoEditar.php";

        } else {
            header("Location: ./control_eventos.php");
        }

    } else if (isset($_POST["borrar"])) {

        $veces=0;
        for ($i=1; $i <= 6; $i++) {
            if(isset($_POST['borrarIDS'.strval($i)])) {
                $asiste-> Desapuntarse($_POST['borrarIDS'.strval($i)],$_POST['id_evento']);
                $veces++;
            }
        }
        
        if ($veces!=0) {
            header('Location: ./control_eventoEditar.php?ver='.$_POST['id_evento'].'&bien');
        } else {
            header('Location: ./control_eventoEditar.php?ver='.$_POST['id_evento'].'&error=4');
        }
            
    } elseif (isset($_POST["publicar"])) {

        $titulo = trim($_POST['titulo']);
        $cuerpo = nl2br(trim($_POST['cuerpo']));
        $fecha=($_POST['date']);
        $tipo = $_POST['tipo'];

       
        if ($tipo == 0) {
            $lugar = "";
            $aforo = -1;
        } else {
            if (isset($_POST['municipio'])) {
                $lugar = $_POST['municipio'];
            } else {
                header('Location: ./control_eventoEditar.php?entrarPublicar&error=5');
            }
            if (isset($_POST['aforo'])) {
                $aforo = $_POST['aforo'];
            } else {
                header('Location: ./control_eventoEditar.php?entrarPublicar&error=4');
            }
        }

        $id_evento_nuevo = $evento->sacar_id_correspondiente_evento();

        if ($titulo!="" and $cuerpo!="") {
            if ($_FILES['imagen']['size']!=0) {
                $tipoImg = strstr($_FILES['imagen']["type"], "image");
    
                //Comprobamos que el archivo subido es de tipo imagen
                if ($tipoImg !="") {
                    $nameOriginal = $_FILES['imagen']['name'];
    
                    //le damos nombre a la imagen y preparamos para subirla
                    //el nombre de las fotos de perfil siempre seran el id del usuario
                    $extension = strpbrk($nameOriginal, ".");
                    $portada = $id_evento_nuevo.$extension;
    
                    $evento->insertar_evento($titulo,$fecha,$cuerpo,$tipo,$portada,$id_usuario,$aforo,$lugar);
    
                    $temp = $_FILES['imagen']['tmp_name'];
                    $ruta = "../imagenes/fotos_eventos/".$portada;
                    move_uploaded_file ($temp,$ruta);


                    header('Location: ./control_eventoLeer.php?ver='.$id_evento_nuevo);
                //Si me sube una archivo que no sea una imagen devolvemos un error 
                } else {
                    header('Location: ./control_eventoEditar.php?entrarPublicar&error=3');
                }
            } else {
                header('Location: ./control_eventoEditar.php?entrarPublicar&error=2');
            }
        } else {
            header('Location: ./control_eventoEditar.php?entrarPublicar&error=1');
        }

    } elseif (isset($_POST["editar"])) {

        $titulo = trim($_POST['titulo']);
        $cuerpo = nl2br(trim($_POST['cuerpo']));
        $fecha=($_POST['date']);
        $tipo = $_POST['tipo'];
        $id_evento = $_POST['id_evento'];
        $portada = $_POST['portada'];

       
        if ($tipo == 0) {
            $lugar = "";
            $aforo = -1;
        } else {
            if (isset($_POST['municipio'])) {
                $lugar = $_POST['municipio'];
            } else {
                header('Location: ./control_eventoEditar.php?ver='.$id_evento.'&error=5');
            }
            if (isset($_POST['aforo'])) {
                $aforo = $_POST['aforo'];
            } else {
                header('Location: ./control_eventoEditar.php?ver='.$id_evento.'&error=4');
            }
        }

        if ($titulo!="" and $cuerpo!="") {
            if ($_FILES['imagen']['size']!=0) {
                $tipoImg = strstr($_FILES['imagen']["type"], "image");
    
                //Comprobamos que el archivo subido es de tipo imagen
                if ($tipoImg !="") {
                    $nameOriginal = $_FILES['imagen']['name'];
    
                    //le damos nombre a la imagen y preparamos para subirla
                    //el nombre de las fotos de perfil siempre seran el id del usuario
                    $extension = strpbrk($nameOriginal, ".");
                    $portada = $id_evento.$extension;

                    //Borramos la portada anterior
                    array_map('unlink', glob('../imagenes/fotos_eventos/'.$portada));
    
                    $evento->modificar_evento($titulo,$fecha,$cuerpo,$tipo,$portada,$id_usuario,$aforo,$lugar,$id_evento);
    
                    $temp = $_FILES['imagen']['tmp_name'];
                    $ruta = "../imagenes/fotos_eventos/".$portada;
                    move_uploaded_file ($temp,$ruta);


                    header('Location: ./control_eventoLeer.php?ver='.$id_evento);
                //Si me sube una archivo que no sea una imagen devolvemos un error 
                } else {
                    header('Location: ./control_eventoEditar.php?ver='.$id_evento.'&error=3');
                }
            } else {
                $evento->modificar_evento($titulo,$fecha,$cuerpo,$tipo,$portada,$id_usuario,$aforo,$lugar,$id_evento);
                header('Location: ./control_eventoLeer.php?ver='.$id_evento);
            }
        } else {
            header('Location: ./control_eventoEditar.php?ver='.$id_evento.'&error=1');
        }

    } else {
        header('Location: ./control_eventos.php');
    }

?>