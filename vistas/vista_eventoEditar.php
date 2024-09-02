<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);

?>

<main>
            
    <section class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <h1 class="texto-head-oscuro"> 
                    <?php 
                        if (isset($datos_e)) {
                            echo "EDITAR EVENTO";
                        } else {
                            echo "CREAR EVENTO";
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
                <div class="row flex-column align-items-center justify-content-center">
                    <div class="col-md-10 text-center mt-4">
                        <a class="btn btn-outline-info" href="./control_eventoLeer.php?ver='.$_GET["ver"].'">
                            Leer evento
                        </a>
                    </div>
                    
                    <div class="col-md-10 text-center mt-4">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Ver asistentes
                            </button>
                    </div>
                    <div class="col-md-10 text-center mt-4">
                        <div class="accordion-item ">
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">';

                                if (count($datos_a)!=0) {

                                    echo '<form action="./control_eventoEditar.php" method="POST">

                                    <div class="row">
                                        <table class="table">
                                            <tr class="table-dark text-center">
                                                <th>
                                                    Id usuario
                                                </th>
                                                <th scope="col" colspan="3">
                                                    Usuario apuntado
                                                </th>
                                                <th scope="col">
                                                    <button type="button" class="btn btn-outline-danger fa fa-trash" style="font-size:36px" data-bs-toggle="modal" data-bs-target="#borrar">
                                                    </button>
                                                </th>
                                            </tr>';

                                } else {
                                    echo '<p> Aún no hay usuarios apuntados... </p>';
                                }
                                                $i=1;
                                                foreach ($datos_a as $datoC) {
                                                    $tipo = nombre_tipos_de_usuario($datoC['tipo']);
                                                    echo '
                                                    <tr class="table-secondary text-center">
                                                        <th>
                                                            '.$datoC["id"].'
                                                        </th>
                                                        <td>
                                                            <img src="../imagenes/foto_usuario/'.$datoC["foto"].'" alt="avatar" width="40" height="40">
                                                        </td>
                                                        <td>
                                                            <a href="./control_usuario_ver.php?ver='.$datoC["id"].'">
                                                                '.$datoC['nombre'].' 
                                                             </a>
                                                        </td>
                                                        <td>
                                                            <div class=" d-flex mb-0 ms-2">
                                                                <span class="badge '.$tipo[1].'"> '.$tipo[0].' </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input" type="checkbox" name="borrarIDS'.$i.'" value="'.$datoC["id"].'" id="flexCheckDefault">
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
                                                                        <input type="hidden" name="id_evento" value="'.$_GET["ver"].'">
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
                </div>
                ';
            } 
        ?> 

        <form class="row flex-column align-items-center justify-content-center" action="./control_eventoEditar.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-5 text-center">
                <label for="titulo" class="form-label mt-4"><h3>Titulo</h3></label>
                <input type="text" class="form-control" name='titulo' id="titulo" placeholder="Escribe algo" maxlength="53"
                value="<?php if (isset($datos_e)) {echo $datos_e["titulo"];}?>" required>
            </div>

            <div class="col-md-3 text-center mt-3">
                <label for="portada" class="form-label mt-4"><h3>Portada</h3></label>
                <img class="img-fluid" id="portada-preview"
                src="
                <?php 
                    if (isset($datos_e)) {
                        echo "../imagenes/fotos_eventos/".$datos_e["foto_portada"];
                    } else  {
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

            <div class="col-md-5 text-center mt-3">
                <label for="date" class="form-label mt-2"><h3>Fecha del evento</h3></label>
                <?php 
                    if(isset($datos_e)) {

                        if($datos_e['fecha'] >= $fechaAhora) {
                            echo '
                            <input type="date" class="form-control" id="date" name="date" min="'.$fechaAhora.'" value="'.$datos_e["fecha"].'">
                            ';
                        } else {
                            echo '<input class="form-control" id="disabledInput" type="text" placeholder="'.$datos_e["fechaEspana"].'" disabled="">';
                        } 
                        
                    } else {
                        echo '
                        <input type="date" class="form-control" id="date" name="date" min="'.$fechaAhora.'">
                        ';
                    }
                ?>
                
            </div>

            
            <div class="col-md-10 text-center mt-4">
                <h3>Tipo de Evento</h5>
                <div class="d-flex align-items-center justify-content-center">
                    <div class="form-check m-1">
                        <input class="form-check-input" type="radio" name="tipo" id="optionsRadios1" value="1" checked data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <label class="form-check-label" for="optionsRadios1">
                        Presencial
                        </label>
                    </div>
                    <div class="form-check m-1">
                        <input class="form-check-input" type="radio" name="tipo" id="optionsRadios2" value="0" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <label class="form-check-label" for="optionsRadios2">
                        Virtual
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-10 text-center mt-4">
                <div class="accordion-item">
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <h3>Lugar del evento</h3>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <label for="listaProvincias" class="form-label">Provincia</label>
                                    <select class="form-select" id="listaProvincias" name="provincia">
                                    </select>
                                </div>
                                <div class="col-md">
                                    <label for="listaMunicipios" class="form-label">Municipio</label>
                                    <select class="form-select" id="listaMunicipios" name="municipio">
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <h3>Aforo</h3>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name='aforo' id="aforo" placeholder="Pon aqui el aforo" 
                                    value="<?php if (isset($datos_e)) {echo $datos_e["aforo"];}?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md text-center">
                <label for="cuerpo" class="form-label mt-4"><h3>Cuerpo</h3></label>
                <textarea class="form-control" name="cuerpo" id="cuerpo" rows="10" required><?php if (isset($datos_e))  {echo str_replace("<br />", "",$datos_e["descripcion"]);}?></textarea>
            </div>

            <div class="col-md text-center">
                <?php 
                    if (isset($_GET["entrarPublicar"])) {
                        echo '<button type="submit" class="btn btn-outline-info" name="publicar">Publicar</button>';
                    } elseif (isset($_GET["ver"])) {
                        echo '<input type=hidden name="id_evento" value='.$_GET["ver"].'>';
                        echo '<input type=hidden name="portada" value='.$datos_e["foto_portada"].'>';
                        echo '<button type="submit" class="btn btn-outline-info" name="editar">Guardar cambios</button>';
                    }
                ?>
            </div>
        </form>
    </section>

</main>

<?php 

    pintar_footer(false);
    
?> 