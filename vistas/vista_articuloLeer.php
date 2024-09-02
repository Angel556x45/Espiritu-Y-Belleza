<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);

?>

    <main>

        <section class="container-fluid articulo_portada p-5 d-flex align-items-end justify-content-center" style=

        "
        background: linear-gradient(
        rgba(0, 0, 0, 0.0),
        rgba(0, 0, 0, 1)
        ), 
        url(../imagenes/fotos_articulos/<?php echo $datosA["id"] ."/". $datosA["portada"]; ?>)
        ; 
        background-size: cover;
        background-attachment:fixed;">

            <div class="row w-100 d-flex justify-content-center">

                <div class="col-md-5">
                    <h1 class="texto-head-claro"> 
                        <?php 
                        echo $datosA["titulo"];
                            if ($tipo_usuario!=-1) {
                                if ($exsiste_favorito==1) {
                                    echo '
                                    <a class="fa fa-heart text-decoration-none texto-favorito " href="./control_articuloLeer.php?borrarFav='.$datosA["id"].'"> 
                                    </a> 
                                    ';
                                } else {
                                    echo '
                                    <a class="fa fa-heart-o text-decoration-none texto-head-claro" href="./control_articuloLeer.php?añadirFav='.$datosA["id"].'"> 
                                    </a> 
                                    ';
                                }
                            }
                        ?> 

                    </h1>
                    <?php 
                        if ($datosA["tipo"]=='1') {
                            echo '<span class="badge rounded-pill bg-success"> Articulo Oficial </span>';
                        } else {
                            echo '<span class="badge rounded-pill bg-info"> Articulo Comunitario </span>';
                        }
                    ?> 
                    <div class="d-flex align-items-center justify-content-evenly mt-2">
                        <img src="../imagenes/foto_usuario/<?php echo $datosAutor['foto']; ?>" class="img-fluid foto_perfil_mini">
                        <p class="texto-head-claro"> <?php echo $datosAutor['nombre'] ." • ". $datosA["fechaEspana"]; ?> </p>
                        <?php 
                            if ($datosA["id_usuario"]==$id_usuario || $tipo_usuario==0 || $tipo_usuario==1) {
                                echo '                    
                                <a class="btn btn-outline-success" href="./control_articuloEditar.php?entrarEdicion='.$datosA["id"].'"> 
                                    EDITAR ARTICULO
                                </a>  ';
                            }
                        ?>
                    </div>
                </div>

            </div>
        
        </section>

        <section class="container-md mt-5">

            <div class="row d-flex justify-content-center">

                <div class="col-md-10">

                    <p>
                        <?php echo $datosA["cuerpo"] ?>
                    </p>
                    

                </div>

            </div>

        </section>

        <?php 
                if ($datosF!=null) {

                    echo '
                    <section class="container-md">
                        <div class="row d-flex justify-content-center">
                            <h2 class="text-center">MÁS IMÁGENES</h2>
                            <div class="col-md-8">
                                <div id="carouselExample" class="carousel slide">
                                    <div class="carousel-inner">';
                                    $primero=true;
                                    foreach ($datosF as $datoF) {
                                        if ($primero) {
                                        echo '                 
                                        <div class="carousel-item active">
                                            <img src="'.$datoF['url'].'" class="d-block w-100" alt="...">
                                        </div>';
                                        $primero=false;
                                        } else {
                                        echo '                 
                                        <div class="carousel-item">
                                            <img src="'.$datoF['url'].'" class="d-block w-100" alt="...">
                                        </div>';
                                        }
                                    }
                            echo '
                                </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            </div>
                        </div>
                    </div>
                </section>
                    ';
                }
        
        ?>

        <section class="container-md mt-5">

            <div class="row d-flex justify-content-center">
                <div class="col-md-10 text-center">
                    <h2>Artículos recomendados</h2>
                </div>
            </div>

            <div class="row d-flex justify-content-center">

            <?php 
                for ($i=0; $i<=2; $i++) {
                    echo ' 
                    <div class="col-md-3">
        
                        <div class="card mb-3">
                            <h3 class="card-header header-eventos d-flex align-items-center justify-content-center">'. $datosRecomendados[$i]["titulo"] .'</h3>
        
                            <div class="foto_card" style="background-image: url(../imagenes/fotos_articulos/'.$datosRecomendados[$i]["id"] .'/'. $datosRecomendados[$i]["portada"].')">
                            </div>
        
                            <div class="card-body text-center">
                                <form action="control_articuloLeer.php" method="GET">
                                <button type="submit" name="ver" value=" '.$datosRecomendados[$i]["id"].' " class="btn btn-outline-info"> LEER </button>
                                </form>
                            </div>
                        </div>
        
                    </div>
                    ';
                }
            ?>

            </div>

        </section>

        <section class="container-md">

        <div class="row d-flex justify-content-center">
            <div class="col-md-10 text-center">
                <h2>Comentarios</h2>
            </div>

        </div>


        <?php 
            if ($existe_comentario==0 && $id_usuario!=-1) {

                if (isset ($_GET['error'])) {

                    echo '<section class="container">';
                    switch($_GET['error']) {
                        case 1:
                            echo '    
                            <div class="alert alert-dismissible alert-warning text-center">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <p> <strong>Error</strong> No puedes escribir un comentario vacío </p>
                            </div>';
                            break;  
                    }
                    echo '</section>';
                }   

                echo '
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-body">
                            
                                <form action="./control_articuloLeer.php" method="POST">
            
                                    <div>
                                        <label for="exampleTextarea" class="form-label mt-4">Escribe aquí tu comentario</label>
                                        <textarea class="form-control" id="exampleTextarea" name="cuerpo" rows="2"></textarea>
                                    </div>
            
                                    <input type="hidden" name="id_usuario" value="'.$id_usuario.'">
                                    <input type="hidden" name="id_tabla" value="'.$_GET['ver'].'">
                                    <button type="submit" name="escribir_comentario" class="btn btn-success">PUBLICAR</button>
            
                                </form>
                            
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
        ?>



        <div class="row d-flex flex-column align-items-center justify-content-center" id="comentarios">
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <ul class="pagination">

                    <form action="./control_articuloLeer.php" method="GET" class="d-flex">

                        <?php 
                            echo '<input type="hidden" name="ver" value='.$_GET['ver'].'>';
                            echo '<input type="hidden" name="pagina_actual" value='.$pagina_actual.'>';
                        ?>

                        <?php 
                            if ($pagina_actual==1) {
                                echo '<li class="page-item disabled">';
                            } else {
                                echo '<li class="page-item">';
                            }
                        ?>
                            <input type="submit" name="atras" value ="&laquo;"class="page-link" href="#"></input>
                        </li>
                        <li class="page-item active">
                            <p class="page-link"><?php echo $pagina_actual; ?></p>
                        </li>

                        <?php 
                            if ($pagina_actual>=$num_paginas) {
                                echo '<li class="page-item disabled">';
                            } else {
                                echo '<li class="page-item">';
                            }                     
                        ?>
                            <input type="submit" name="delante" value = "&raquo;" class="page-link" href="#"></input>
                        </li>

                    </form>
                </ul>
                </div>
            </div>
        </div>

        </section>

    </main>

<?php 

    pintar_footer(false);

?> 