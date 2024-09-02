<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_usuario.php";
    require_once "../modelos/modelo_articulo.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $usuario = new modelo_usuario();
    $articulo = new modelo_articulo;

    $datosU= $usuario->buscar_usuario_por_id($id_usuario);
    $datosFavoritos = $articulo->mostrar_articulos_favoritos($id_usuario);

    if ($tipo_usuario==-1) {
        header('Location: ../index.php');
    }

    //funciones para cambiar la foto y la contraseña

    if (isset($_POST["cambiar_pass"])) {

        $funciona = $usuario->modificar_password($id_usuario, $_POST['pass_old'], $_POST['pass_new']);

        if ($funciona) {
            header('Location: ./control_mi_perfil.php?bien=1');
        } else {
            header('Location: ./control_mi_perfil.php?error=1');
        }

    }

    if (isset($_POST['cambiar_imagen'])) {


        if ($_FILES['imagen']['size']!=0) {

            echo 
            $tipo = strstr($_FILES['imagen']["type"], "image");

            //Comprobamos que el archivo subido es de tipo imagen
            if ($tipo !="") {
    
                $nameOriginal = $_FILES['imagen']['name'];

                //le damos nombre a la imagen y preparamos para subirla
                //el nombre de las fotos de perfil siempre seran el id del usuario
                $extension = strpbrk($nameOriginal, ".");
                $nueva_img = $id_usuario.$extension;

                $usuario->modificar_foto_usuario_personal($id_usuario,$nueva_img);

                //borramos cualquier cualquier archivo que tenga de nombre el id del usuario 
                //para no acumular fotos de diferentes formatos
                array_map('unlink', glob('../imagenes/foto_usuario/'.$id_usuario.'.*'));
                
                $temp = $_FILES['imagen']['tmp_name'];
                $ruta = "../imagenes/foto_usuario/".$nueva_img;
                move_uploaded_file ($temp,$ruta);

                header('Location: ./control_mi_perfil.php?bien=2');


            //Si me sube una archivo que no sea una imagen devolvemos un error 
            } else {
                header('Location: ./control_mi_perfil.php?error=3');
            }

        } else {
            header('Location: ./control_mi_perfil.php?error=2');
        }
    
    }

    include "../vistas/vista_mi_perfil.php"

?>