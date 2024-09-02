<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_evento.php";
    require_once "../modelos/modelo_asiste.php";

    $fecha=date('Y-m-d');
    $fechaAhora=date('Y-m-d');

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $evento = new modelo_evento();
    $asiste = new modelo_asiste();

    if (isset($_GET['ver'])) {

        $datosE = $evento->buscar_evento_por_id($_GET['ver']);

        $asistentes = $asiste->mostrar_asistentes($_GET['ver']);

        $asistentes_totales=strval(count($asistentes));

        $exsiste = $asiste->mostar_usuario($_GET['ver'],$id_usuario);

        crearJSON($datosE, 'evento',false);

    } elseif (isset($_POST['apuntar'])) {

        $asiste->apuntarse($id_usuario,$_POST['apuntar']);

        header('Location: ./control_eventoLeer.php?ver='.$_POST['apuntar']);
    } else {
        header('Location: ../index.php');
    }

    include "../vistas/vista_eventoLeer.php";

 ?>