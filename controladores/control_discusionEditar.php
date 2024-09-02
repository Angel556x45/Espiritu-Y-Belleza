<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_discusion.php";
    require_once "../modelos/modelo_usuario.php";
    require_once "../modelos/modelo_etiqueta.php";
    require_once "../modelos/modelo_tiene_X.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $discusion = new modelo_discusion;
    $usuario = new modelo_usuario;
    $etiqueta = new modelo_etiqueta();
    $tiene_d = new modelo_tiene_X();

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

            include "../vistas/vista_discusionEditar.php";

        } else {
            header("Location: ./control_discusiones.php");
        }

    //Para cuando vamos a editar uno nuevo
    }  elseif (isset($_POST["publicar"])) {

        $titulo = trim($_POST['titulo']);
        $cuerpo = nl2br(trim($_POST['cuerpo']));
        $fecha=date('Y-m-d');

        $id_discusion_nueva = $discusion->sacar_id_correspondiente_discusion();

        if ($titulo!="" and $cuerpo!="") {
            $discusion->insertar_discusion($titulo,$cuerpo,$fecha,$id_usuario);
            $cantidad_etiquetas = $etiqueta->contar_etiquetas();
            for ($i=1; $i <= $cantidad_etiquetas; $i++) {
                if(isset($_POST[strval($i)])) {
                    $tiene_d->crear_tiene_x(false,$id_discusion_nueva,$i);
                }
            }
            header('Location: ./control_discusionLeer.php?ver='.$id_discusion_nueva);

        } else {
            header('Location: ./control_discusionEditar.php?entrarPublicar&error=1');
        }

    //Cuando presionamos el boton de editar
    } else {
        header('Location: ./control_discusiones.php');
    }

?>