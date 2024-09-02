<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);

    $tipo = nombre_tipos_de_usuario($datosAutor['tipo']);

?>

<main>

    <section class="container-fluid p-5 d-flex justify-content-center align-items-center" style="background-image: url(../imagenes/fotos_estilo/discusiones_front.jpg);">
        <div class="justify-content-center align-items-center text-center">
            <div class="col-md-12">
                <h1 class="texto-head-claro">DISCUSION</h1>
            </div>
            <div class="col-md-12">
                <small class="texto-head-claro"><?php echo $datosD["titulo"]; ?></small>
            </div>
        </div>
    </section>

    <section class="container-md mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <img src="../imagenes/foto_usuario/<?php echo $datosAutor["foto"] ?>" alt="avatar" width="25" height="25">
                                
                                <h5 class="mb-0 ms-2"> <?php echo $datosAutor["nombre"]; ?></h5>

                                <div class=" d-flex mb-0 ms-2">
                                    <span class="badge <?php echo $tipo[1]; ?>"><?php echo $tipo[0]; ?></span>
                                </div>
                 

                            </div>
                            <div class="d-flex flex-row align-items-center">
                                <p class="small text-muted mb-0">
                                    <?php 
                                        echo calcular_diferencia_fecha(date_create($datosD["fecha"]),true);
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <p> <?php echo $datosD["cuerpo"]; ?> </p>    
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="container-md mt-3">

        <div class="row d-flex justify-content-center">
            <div class="col-md-10 text-center">
                <h2>Respuestas</h2>
            </div>
        </div>

        <?php 
            if ($id_usuario!=-1) {

                echo '
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-body">
                            
                                <form action="./control_discusionLeer.php" method="POST">
            
                                    <div>
                                        <label for="exampleTextarea" class="form-label mt-4">¿Quieres responder?</label>
                                        <textarea class="form-control" id="exampleTextarea" name="cuerpo" rows="2"></textarea>
                                    </div>
            
                                    <input type="hidden" name="id_usuario" value="'.$id_usuario.'">
                                    <input type="hidden" name="id_tabla" value="'.$_GET['ver'].'">
                                    <button type="submit" name="escribir_comentario" class="btn btn-success">RESPONDER</button>
            
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

                    <form action="./control_discusionLeer.php" method="GET" class="d-flex">

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