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
            url(../imagenes/fotos_eventos/<?php echo $datosE["foto_portada"]; ?>)
            ; 
            background-size: cover;
            background-position: center;">

                <div class="row w-100 d-flex justify-content-center">

                    <div class="col-md-8">
                        <h1 class="texto-head-claro"> <?php echo $datosE["titulo"]; ?> </h1>

                        <small class="texto-head-claro"> FECHA:  <?php echo $datosE["fechaEspana"] ?> </small>

                        <div class="mt-3">
                            <?php 
                                if ($datosE["tipo"]==1) {
                                    echo '<span class="badge rounded-pill bg-success"> Evento presencial </span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-info"> Evento Virtual </span>';
                                }
                            ?>                         
                            <?php 
                                if ($datosE['aforo']!=-1) {
                                    echo '
                                    <span class="badge rounded-pill bg-warning">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-map-marker" id="lugar"> BAZA/MALAGA</i>
                                        <div>
                                    </span>
                                    ';
                                } 
                            ?>
                        </div>

                    </div>

                </div>
            
        </section>


        <section class="container-md mt-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-10">
                    <p><?php echo $datosE["descripcion"] ?></p>
                </div>
            </div>

            <?php 
                if ($tipo_usuario==0 || $tipo_usuario==1) {
                    echo '                    
                    <div class="d-flex justify-content-center mb-3">
                        <a class="btn btn-Success" href="./control_eventoEditar.php?ver='.$datosE['id'].'">
                            Editar evento
                        </a>
                    </div>';
                }

                if($datosE['fecha'] <= $fechaAhora) {

                } else {
                    if ($datosE['aforo']!=-1) {
                        echo '
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-10 text-center">
                                <h5> Pronostico meteorológico</h5>
                                <div class="text-center" id="tiempo" >
                                    <i class="fa fa-cloud"> Poco nuboso </i>
                                    <br>
                                    <i class="fa fa-thermometer"> 18 Cº </i>
                                </div>
                            </div>
                        </div>
                        ';
                    } 
                }

            ?>

            <div class="row d-flex justify-content-center mt-3">
                <div class="col-md-10 text-center">
                    
                    <?php 

                        if($datosE['fecha'] <= $fechaAhora) {
                            echo '
                            <button type="button" class="btn btn-primary disabled">EVENTO FINALIZADO</button>
                            ';
                        } else {

                            if ($datosE['aforo']<=-1) {
                                echo '<h5> Aforo Ilimitado </h5>';
                            } else {
                                echo ' 
                                    <h5> Plazas restantes: '.($datosE['aforo']-$asistentes_totales).'/'.$datosE['aforo'].'</h5>
                                ';
                            }

                        }

                    ?>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-10">
                    <?php 

                    if($datosE['fecha'] <= $fechaAhora) {
                        echo '
                        <div class="d-flex justify-content-center">
                            <p>No puedes apuntarte a un evento que ya ha ocurrido</p>
                        </div>
                        ';
                    } else {

                        if ($tipo_usuario!=-1 && strval($asistentes_totales!=$datosE['aforo']) && $exsiste==0) {
                            echo '
                            <form action="./control_eventoLeer.php" class="d-flex justify-content-center" method="POST">
                                <button type="submit" name="apuntar" value="'.$datosE['id'].'" class="btn btn-warning"> Apuntame </button>
                            </form>
                            ';
                        } elseif ($tipo_usuario==-1) {
                            echo '
                            <div class="d-flex justify-content-center">
                                <p>Inicia sesión para poder apuntarte</p>
                            </div>
                            ';
                        } elseif (strval($asistentes_totales==$datosE['aforo'])) {
                            echo '
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-warning disabled"> Plazas agotadas </button>
                            </div>
                            ';
                        } elseif ($exsiste==1) {
                            echo '
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-warning disabled"> Ya estas apuntado </button>
                            </div>
                            ';
                        }

                    }

                    ?>

                </div>
            </div>
        </section>

    </main>

<?php 

    pintar_footer(false);

?> 