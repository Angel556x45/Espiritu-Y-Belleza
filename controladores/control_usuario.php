<?php 

    require_once "../bd/bd.php";
    require_once "../funciones.php";
    require_once "../modelos/modelo_usuario.php";
    
    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    $fecha=date('Y-m-d');
    $usuario = new modelo_usuario();

    $datosU = $usuario->listar_usuarios ();

    crearJSON($datosU, 'todos_los_usuarios',false);

    include "../vistas/vista_usuario.php";


?>