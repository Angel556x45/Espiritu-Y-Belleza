<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);

?>

<main>

<?php 
    
    if (isset ($_GET['error'])) {

        echo '<section class="container">';
        switch($_GET['error']) {
            case 1:
                echo '    
                <div class="alert alert-dismissible alert-danger text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <p><strong>Error</strong> El titulo y el cuerpo no pueden estar vacios </p>
                </div>';
                break;
            case 2:
                echo '    
                <div class="alert alert-dismissible alert-danger text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <p><strong>Error</strong> No puedes subir un archivo vacio </p>
                </div>';
                break;
            case 3:
                echo '    
                <div class="alert alert-dismissible alert-danger text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <p><strong>Error</strong> Solo puedes subir archivos de tipo imagen </p>
                </div>';
                break;
            case 4:
                echo '    
                <div class="alert alert-dismissible alert-danger text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <p><strong>Error</strong> Tienes que seleccionar al menos 1 comentario para borrar </p>
                </div>';
                break;       
        }
        echo '</section>';
    }

    if (isset($_GET['bien'])) {
        echo '<section class="container">';
        echo '
        <div class="alert alert-dismissible alert-success text-center">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <p><strong>Perfecto</strong> comentarios borrados </p> 
        </div>
    ';
    echo '</section>';
    }
?>
            
    <section class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <h1 class="texto-head-oscuro"> 
                    <?php 
                        if (isset($datos_a)) {
                            echo "EDITAR ARTÍCULO";
                        } else {
                            echo "ESCRIBIR ARTÍCULO";
                        }
                    ?> 
                </h1>
            </div>
        </div>
    </section>

    <section class="container-lg">

        <?php 
            if (isset($datos_a)) {
                echo '
                <form class="row flex-column align-items-center justify-content-center" action="./control_articuloEditar.php" method="POST" enctype="multipart/form-data">
                    <div class="col-md-10 text-center mt-4">
                    
                            <a class="btn btn-outline-info" href="./control_articuloLeer.php?ver='.$datos_a["id"].'">
                                Leer artículo
                            </a>

                            <button class="btn btn-outline-primary"" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Ver comentarios
                            </button>
                    </div>
                    <div class="col-md-10 text-center mt-4">
                        <div class="accordion-item ">
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">';

                                if (count($datosC)!=0) {

                                    echo '<form action="./control_articuloEditar.php" method="POST">

                                    <div class="row">
                                        <table class="table">
                                            <tr class="table-dark text-center">
                                                <th scope="col" colspan="2">
                                                    Escrito por
                                                </th>
                                                <th scope="col">comentario</th>
                                                <th scope="col">fecha</th>
                                                <th scope="col">
                                                    <button type="button" class="btn btn-outline-danger fa fa-trash" style="font-size:36px" data-bs-toggle="modal" data-bs-target="#borrar">
                                                    </button>
                                                </th>
                                            </tr>';

                                } else {
                                    echo '<p> Aún no hay comentarios... </p>';
                                }
                                                $i=1;
                                                foreach ($datosC as $datoC) {
                                                    echo '
                                                    <tr class="table-secondary text-center">
                                                        <th>
                                                            <img src="../imagenes/foto_usuario/'.$datoC["foto_usuario"].'" alt="avatar" width="40" height="40">
                                                        </th>
                                                        <th>
                                                            <a href="./control_usuario_ver.php?ver='.$datoC["id_usuario"].'">
                                                                '.$datoC['nombre_usuario'].' 
                                                             </a>
                                                        </th>
                                                        <td>
                                                            '.$datoC['texto'].'
                                                        </td>
                                                        <td>'.$datoC['fechaEspana'].'</td>
                                                        <td>
                                                            <input class="form-check-input" type="checkbox" name="borrarIDS'.$i.'" value="'.$datoC["id_usuario"].'" id="flexCheckDefault">
                                                            <input type="hidden" name="fecha'.$i.'" value="'.$datoC["fecha"].'">
                                                        </td>
                                                    </tr>
                                                    ';
                                                    $i++;
                                                }

                                                echo '
                                                <div class="modal fade" id="borrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                            
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> ¿Esta seguro que quiere borrar estos articulos? </h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                
                                                            <div class="modal-body d-flex justify-content-center">
                                                                    <div class="mt-2">
                                                                        <input type="hidden" name="id_articulo" value="'.$_GET["entrarEdicion"].'">
                                                                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal" aria-label="Close">CANCELAR</button>
                                                                        <button type="submit" class="btn btn-outline-danger" name="borrar">CONFIRMAR BORRAR</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';

                                        echo '</table>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </form>
                ';
            } 
        ?> 
    
        <form class="row flex-column align-items-center justify-content-center" action="./control_articuloEditar.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-5 text-center">
                <label for="titulo" class="form-label mt-4"><h3>Título</h3></label>
                <input type="text" class="form-control" name='titulo' id="titulo" placeholder="Escribe algo"  maxlength="25"
                value="<?php if (isset($datos_a)) {echo $datos_a["titulo"];}?>" required>
            </div>

            <div class="col-md-3 text-center mt-3">
                <label for="portada" class="form-label mt-4"><h3>Portada</h3></label>
                <img class="img-fluid" id="portada-preview"
                src="
                <?php 
                    if (isset($datos_a)) {
                        echo "../imagenes/fotos_articulos/".$_GET["entrarEdicion"]."/".$datos_a["portada"];
                    } else {
                        echo "https://bit.ly/3ubuq5o";
                    }
                ?>"
                alt="portada" >
                <input class="form-control" name='imagen' type="file" id="portada" 
                <?php 
                    if(isset($_GET["entrarPublicar"])) {
                        echo 'required';
                    }
                ?>
                >
            </div>

            <div class="col-md text-center mt-3">
                <h4>Etiquetas</h4>
                <div class="d-flex flex-wrap justify-content-center" id="etiquetas">
                    <?php 
                        echo $divs_de_etiquetas;
                    ?>
                </div>
            </div>

            <div class="col-md text-center">
                <label for="cuerpo" class="form-label mt-4"><h3>Cuerpo</h3></label>
                <textarea class="form-control" name="cuerpo" id="cuerpo" rows="20" required><?php if (isset($datos_a)) {echo str_replace("<br />", "",$datos_a["cuerpo"]);}?></textarea>
            </div>

            <div class="col-md text-center mt-3 mb-3">
                <h3>Más imágenes</h3>
                <div class="row justify-content-center">
                    <?php 
                        if (isset($datos_f)) {
                            for ($i=0;$i<6;$i++) {
                                if (isset($datos_f[$i])) {
                                    echo '<div class="col-md-4">';
                                    echo    '<img class="img-fluid" src="'.$datos_f[$i]['url'].'" alt="imagen" id="file-'.$i.'-preview">';
                                    echo    '<input class="form-control" name="foto'.$i.'" type="file" id="file-'.$i.'">
                                          </div>';
                                    echo '<input type=hidden name="exsiste'.$i.'">';
                                } else {
                                    echo '<div class="col-md-4">
                                            <img class="img-fluid" src="https://bit.ly/3ubuq5o" alt="imagen" id="file-'.$i.'-preview">
                                            <input class="form-control" name="foto'.$i.'" type="file" id="file-'.$i.'">
                                        </div>';
                                }
                            }
                        } else {
                            for ($i=0;$i<6;$i++) {
                                echo 
                                '<div class="col-md-4">
                                    <img class="img-fluid" src="https://bit.ly/3ubuq5o" alt="imagen" id="file-'.$i.'-preview">
                                    <input class="form-control" name="foto'.$i.'" type="file" id="file-'.$i.'">
                                </div>';
                            }
                        }
                    ?>
                </div>
            </div>

            <div class="col-md text-center">
                <?php 
                    if (isset($_GET["entrarPublicar"])) {
                        echo '<button type="submit" class="btn btn-outline-info" name="publicar">Publicar</button>';
                    } elseif (isset($_GET["entrarEdicion"])) {
                        echo '<input type=hidden name="id_articulo" value='.$_GET["entrarEdicion"].'>';
                        echo '<button type="submit" class="btn btn-outline-info" name="edicion">Guardar cambios</button>';
                    }
                ?>
            </div>
        </form>
    </section>

</main>

<?php 

    pintar_footer(false);
    
?> 