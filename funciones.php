<?php

    function comprueba_usuario () {

        session_start();

        if (isset($_COOKIE['sesion'])) {
            session_decode($_COOKIE['sesion']);
        }

        if (isset($_SESSION['id_usuario'])) {
            $id = $_SESSION['id_usuario'];
            $tipo = $_SESSION['tipo_usuario'];
        } else {
            $id = -1;
            $tipo = -1;
        }

        $devolver=array($id,$tipo);

        return $devolver;

    }

    function calcular_diferencia_fecha ($fechaCalcular, $lugar) {
        $fechaAhora = date_create("now");
        $diferencia = date_diff($fechaCalcular,$fechaAhora);
        $valor=array();
        foreach ($diferencia as $e) {
            $valor[]=$e;
        }
        //Para los comentarios de articulos
        if ($lugar) {
            if ($valor[0]==0 && $valor[1]==0) {
                if ($valor[2]==0) {
                    $mensaje="Justo ahora";
                }elseif ($valor[2]==1) {
                    $mensaje="Hace ".$valor[2]. " dia";
                } else {
                    $mensaje="Hace ".$valor[2]. " dias";
                }
            } elseif ($valor[0]==0 && $valor[1]>0) {
                if ($valor[1]==1) {
                    $mensaje="El mes pasado";
                } else {
                    $mensaje="Hace ".$valor[1]. " meses";
                }
            } elseif ($valor[0]>0) {
                if ($valor[0]==1) {
                    $mensaje="El año pasado";
                } else {
                    $mensaje="Hace ".$valor[0]. " años";
                }
            }
        //Para las respuestas de las discusiones    
        } else {
            if ($valor[0]==0 && $valor[1]==0) {
                if ($valor[2]==0 && $valor[3]==0 && $valor[4]==0) {
                    if ($valor[5]==0) {
                        $mensaje= "Ahora mismo";
                    } else {
                        $mensaje= "Hace ".$valor[5]. " segundos";
                    }
                } elseif($valor[2]==0 && $valor[3]==0) {
                    $mensaje= "Hace ".$valor[4]. " minutos";
                } elseif ($valor[2]==0) {
                    $mensaje= "Hace ".$valor[3]. " horas";
                } elseif ($valor[2]==1) {
                    $mensaje="Hace ".$valor[2]. " dia";
                } else {
                    $mensaje="Hace ".$valor[2]. " dias";
                }
            } elseif ($valor[0]==0 && $valor[1]>0) {
                if ($valor[1]==1) {
                    $mensaje="El mes pasado";
                } else {
                    $mensaje="Hace ".$valor[1]. " meses";
                }
            } elseif ($valor[0]>0) {
                if ($valor[0]==1) {
                    $mensaje="El año pasado";
                } else {
                    $mensaje="Hace ".$valor[0]. " años";
                }
            }
        }
        
        return $mensaje;
    }

    //Para algunas paginas es mejor mostrar los datos como un json
    //Esta funcion hace eso mismo con la matriz de datos que sale de las consultas
    function crearJSON ($datos, $nombreArchivo, $index) {
        if ($index) {
            $ruta=".";
        } else {
            $ruta="..";
        }
        //La variable $datos es la matriz de datos
        //La variable $nombreArchivo es el nombre que tendra el archivo json
        //como habra multiples archivos json (articulo.json, discusiones.json,  )

        //limpiamos la carpeta de json para actualizarla
        array_map('unlink', glob($ruta.'/json/'.$nombreArchivo.'.json'));

        //encodeamos la matriz
        $json = json_encode($datos, JSON_UNESCAPED_UNICODE);

        //Guardamos en el servidor el json
        file_put_contents($ruta.'/json/'.$nombreArchivo.'.json', $json);

    }

    //Como los tipo de usuario (roles) estan como numeros en la base de datos
    //esta funcion nos devuelve sus nombres y clase css como un array de strings, para ponerlos donde nos haga falta
    function nombre_tipos_de_usuario ($tipo) {
        $nombre=[];
        switch ($tipo) {
            case '0':
                $nombre=['Administrador', 'bg-danger'];
                break;
            case '1':
                $nombre=['Moderador', 'bg-info'];
                break;
            case '2':
                $nombre=['Predicador', 'bg-success'];
                break;
            case '3':      
                $nombre=['Laico', 'bg-dark'];
                break;    
        }
        return $nombre;
    }


    function pintar_menu ($index, $tipo, $id_usuario) {

        //comprobamos si estamos en el index para la ruta de los enlaces
        if ($index) {
            $ruta=".";
        } else {
            $ruta="..";
        }

        echo '<html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Espiritu y belleza</title>
            <link href="https://bootswatch.com/5/lux/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link href="'.$ruta.'/style.css" rel="stylesheet">
        </head>
        <body>
            <header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom">
                <div class="col-md-2 d-flex justify-content-center">
                    <a href="'.$ruta.'/index.php" class="mb-2 mb-md-0 text-dark text-decoration-none">
                        <img src="'.$ruta.'/imagenes/fotos_estilo/logo.png" width="120" height="120">
                    </a>
                </div>'
        ;

        //Pintamos los menus para cada tipo de usuario
        switch ($tipo) {

            //menu del admin
            case 0: 
                echo '     
                        <ul class="nav col-md-8 mb-2 justify-content-center mb-md-0">
                            <li class="nav-item px-2 link-dark">
                                <a class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">PANEL ADMINISTRADOR</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_articulos.php">Artículos</a>
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_discusiones.php">Discusiones</a>
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_eventos.php">Eventos</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_usuario.php">Usuarios</a>
                                </div>
                            </li>
                        </ul>
            
                        <form action="#" method="POST" class="col-md-2 text-center">
                            <a href="'.$ruta.'/controladores/control_mi_perfil.php" class="btn btn-outline-primary">Mi perfil</a>
                            <button type="submit" name="cerrar" class="btn btn-outline-danger">Cerrar sesion</button>
                        </form>

                    </header>';
                break;
            //menu del no registrado    
            case -1:
                echo '     
                        <ul class="nav col-md-8 mb-2 justify-content-center mb-md-0">
                        <li><a href="'.$ruta.'/controladores/control_articulos.php" class="nav-link px-2 link-dark">Artículos</a></li>
                        <li><a href="'.$ruta.'/controladores/control_discusiones.php" class="nav-link px-2 link-dark">Discusiones</a></li>
                        <li><a href="'.$ruta.'/controladores/control_eventos.php" class="nav-link px-2 link-dark">Eventos</a></li>
                        </ul>
                
                        <div class="col-md-2 text-end">
                            <a href="'.$ruta.'/vistas/vista_login.php" class="btn btn-outline-primary me-2">Acceder</a>
                        </div>
                    </header>';
                    break;
            //menu del usuario moderador        
            case 1:
                echo '     
                        <ul class="nav col-md-8 mb-2 justify-content-center mb-md-0">
                            <li class="nav-item px-2 link-dark">
                                <a class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">PANEL ADMINISTRADOR</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_articulos.php">Artículos</a>
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_discusiones.php">Discusiones</a>
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_eventos.php">Eventos</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="'.$ruta.'/controladores/control_usuario.php">Usuarios</a>
                                </div>
                            </li>
                        </ul>
            
                        <form action="#" method="POST" class="col-md-2 text-center">
                            <a href="'.$ruta.'/controladores/control_mi_perfil.php" class="btn btn-outline-primary">Mi perfil</a>
                            <button type="submit" name="cerrar" class="btn btn-outline-danger">Cerrar sesion</button>
                        </form>

                    </header>';
                break;
            //menu del usuario predicador        
            case 2:
                echo '     
                        <ul class="nav ccol-md-8 mb-2 justify-content-center mb-md-0">
                        <li><a href="'.$ruta.'/controladores/control_articulos.php" class="nav-link px-2 link-dark">Artículos</a></li>
                        <li><a href="'.$ruta.'/controladores/control_discusiones.php" class="nav-link px-2 link-dark">Discusiones</a></li>
                        <li><a href="'.$ruta.'/controladores/control_eventos.php" class="nav-link px-2 link-dark">Eventos</a></li>
                        </ul>
                
                        <form action="#" method="POST" class="col-md-2 text-center">
                            <a href="'.$ruta.'/controladores/control_mi_perfil.php" class="btn btn-outline-primary">Mi perfil</a>
                            <button type="submit" name="cerrar" class="btn btn-outline-danger">Cerrar sesion</button>
                        </form>
                    </header>';
                    break;
            //menu de usuario laico        
            case 3:
                echo '     
                        <ul class="nav col-md-8 mb-2 justify-content-center mb-md-0">
                        <li><a href="'.$ruta.'/controladores/control_articulos.php" class="nav-link px-2 link-dark">Artículos</a></li>
                        <li><a href="'.$ruta.'/controladores/control_discusiones.php" class="nav-link px-2 link-dark">Discusiones</a></li>
                        <li><a href="'.$ruta.'/controladores/control_eventos.php" class="nav-link px-2 link-dark">Eventos</a></li>
                        </ul>
                
                        <form action="#" method="POST" class="col-md-2 text-center">
                            <a href="'.$ruta.'/controladores/control_mi_perfil.php" class="btn btn-outline-primary">Mi perfil</a>
                            <button type="submit" name="cerrar" class="btn btn-outline-danger">Cerrar sesion</button>
                        </form>
                    </header>';
                    break;

        }

        //Aqui pintamos la bandeja de entrada de mensajes
        if ($tipo!=-1) {
            echo '<section>';
                echo '
                <button class="btn message-button" id="messages" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <i style="font-size:30px" class="fa fa-envelope" id="messageIcon"></i>
                </button>
                ';

                echo '
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Bandeja de entrada</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <label class="form-check-label" for="nombre_usuario"><h6> Buscador de usuarios: </h6></label>
                            <input type="text" class="form-control" id="nombre_usuario" placeholder="Escribe el nombre o el rango" autocomplete="off">
                        </div>
                ';

                require_once $ruta."/bd/bd.php";
                require_once  $ruta."/modelos/modelo_mensaje.php";
                require_once  $ruta."/modelos/modelo_usuario.php";

                $mensaje = new modelo_mensaje();
                $usuario = new modelo_usuario();

                $datosUsuarios = $usuario->listar_usuario_activos_menos_yo($id_usuario);

                crearJSON($datosUsuarios,"usuarios_activos",$index);

                //el div mensajes es para la busqueda en tiempo real
                //lo otro es un acordeon para los mensajes que nos han mandado
                $datosM = $mensaje->mostrar_bandeja($id_usuario);
                echo '<div id="mensajes"></div>
                <div class="row mt-4"> 
                    <div class="accordion-item row">
                        <h2 class="accordion-header btn btn-info" id="headingOne">
                            <button class="accordion-button" id="headingButton" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapseOne">
                                Ultimos mensajes recibidos
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                ';
                if (count($datosM)==0) {
                    echo ' <div class="m-3">
                            <p> Aún no tienes mensajes... <p>
                        </div>';
                }

                foreach ($datosM as $datoM) {
                    $datosU = $usuario->buscar_usuario_por_id($datoM["id_usuario_envia"]);
                    $mensaje2 = new modelo_mensaje();
                    $datosUltimo = $mensaje2->mostrar_ultimo_mensaje_recibido($datosU['id'], $id_usuario);

                    //Si el mensaje es muy largo lo acortamos
                    if (strlen($datosUltimo['cuerpo'])>70) {
                        $cuerpo=substr($datosUltimo['cuerpo'],0,-70);
                        $cuerpo=$cuerpo."...";
                    } elseif (strlen($datosUltimo['cuerpo'])>30) {
                        $cuerpo=substr($datosUltimo['cuerpo'],0,-15);
                        $cuerpo=$cuerpo."...";
                    }
                     else {
                        $cuerpo=$datosUltimo['cuerpo'];
                    }


                    $nombre=$datosU['nombre'];
                    

                    $fechaM = calcular_diferencia_fecha(date_create($datosUltimo["fecha"]), false);
                    $tipo = nombre_tipos_de_usuario ($datosU['tipo']);

                    echo '<form action="'.$ruta.'/controladores/control_mensajesLeer.php" method="GET" class="mt-3">';
                    
                    echo '
                        <button type="submit" name="entrar" value='.$datosU['id'].' class="btn btn-light" style="text-transform: none; width: 100%;">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center mb-2">
                                    <img src="'.$ruta.'/imagenes/foto_usuario/'.$datosU['foto'].'" alt="avatar" width="25" height="25">
                                    <div class="d-flex flex-column"> 
                                    <h5 class="mb-0 ms-2 text-start">'.$nombre.'</h5>
                                        <div class=" d-flex mb-0 ms-2">
                                            <span class="badge '.$tipo[1].'"> '.$tipo[0].' </span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <small class="message-preview">'.$fechaM.'</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-start">
                                <small class="message-preview">'.$cuerpo.'</small>
                            </div>
                        </button>
                    ';

                    echo '</form>';
                }

                echo '
                            </div>
                        </div>
                    </div>
                </div>

                
                    </div>
                </div>
                ';

            echo '</section>'; 

        }
    }

    function pintar_ultimos_3_eventos ($index) {

        if ($index) {
            $ruta=".";
        } else {
            $ruta="..";
        }

        require_once  $ruta."/bd/bd.php";
        require_once  $ruta."/modelos/modelo_evento.php";

        $evento = new modelo_evento();

        $datosE = $evento-> mostrar_ultimos_3_eventos();

        foreach ($datosE as $datoE) {

            if (strlen($datoE['titulo'])>60) {
                $nombre=substr($datoE['titulo'],0,-10);
                $nombre=$nombre."...";
            } else {
                $nombre=$datoE['titulo'];
            }

            echo '       
            <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
              <div class="card-header header-eventos d-flex align-items-center justify-content-center" style=" background-position: center;
              background: 
              linear-gradient(
              rgba(0, 0, 0, 0.4),
              rgba(0, 0, 0, 0.4)
              ),
              url('.$ruta.'/imagenes/fotos_eventos/'.$datoE['foto_portada'].');
              ">
                <h4 class="card-title texto-head-claro">'.$nombre.'</h4>
              </div>
              <div class="card-body">
                <a href="http://localhost/spirit/controladores/control_eventoLeer.php?ver='.$datoE['id'].'">
                    <button type="button" class="btn btn-outline-warning">
                        Ver más
                    </button>
                </a>
              </div>
            </div>
          </div>';

        }

    }

    function cerrar_sesion ($index) {

        if (isset($_POST['cerrar'])) {
            if (isset($_COOKIE['sesion'])) {
              setcookie('sesion', '', time()-5, '/');
            }
            session_destroy();
            $_SESSION=[];            
            
            //la variable index de tipo boleano nos indica si estamos en el index o no.
            if ($index) {
               echo "<meta http-equiv='refresh' content='0;url=index.php'>";
            } else {
               echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
            }
          }

    }

    function pintar_footer ($index) {

        //comprobamos si estamos en el index para la ruta de los enlaces
        if ($index) {
            $ruta=".";
        } else {
            $ruta="..";
        }

        echo '
                </main>

                <footer class="d-flex flex-wrap justify-content-center align-items-center py-3 my-4 border-top">
                <p class="col-md-4 mb-0 text-muted">© 2024 Espiritu y belleza</p>
            
                <ul class="nav col-md-4 justify-content-end">
                    <li class="nav-item"><a href="'.$ruta.'/index.php" class="nav-link px-2 text-muted">INICIO</a></li>
                </ul>
                </footer>

                <script src="'.$ruta.'/javascript/script.js"></script>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            </body>
        </html>    
        ';

    }

?>