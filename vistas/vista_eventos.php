<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);
?>

    <main>

    <?php 
    
        if (isset ($_GET['error'])) {

            echo '<section class="container">';

                    echo '    
                    <div class="alert alert-dismissible alert-danger text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <p> <strong>Error</strong> Selecciona al menos un evento </p> 
                    </div>';

            echo '</section>';
        }

        if (isset ($_GET['bien'])) {
            echo '<section class="container">';

                    echo '
                    <div class="alert alert-dismissible alert-success text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <p><strong>Perfecto</strong> se han borrado los eventos seleccionados </p> 
                    </div>
                    ';

            echo '</section>';
        }
    ?>


        <section class="container-fluid paralax-mini eventos-front d-flex justify-content-center align-items-center p-5 ">
            <div class="row text-center d-flex justify-content-center">
                <h1 class="texto-head-claro">EVENTOS</h2>
            </div>
        </section>

        <section class="container border-top border-dark mt-3 mb-3">
            <div class="row d-flex justify-content-center ">
                <div class="col-md-6 text-center">
                    <form action="./control_eventos.php" method="POST">
                        <?php  

                        if ($tipo_usuario==0 || $tipo_usuario==1) {
                            echo'
                            <div>
                            <label for="busquedaID" class="form-label mt-2"><h3>Buscar por ID</h3></label>
                                <input type="text" class="form-control" name="id" id="busquedaID" placeholder="Id del articulo" autocomplete="off">
                            </div>
                            ';
                        } else {
                            echo '<input type="hidden" name="id" value="">';
                        }

                        ?>
                        <div>
                            <label for="busqueda" class="form-label mt-2"><h4>Buscar evento por titulo</h4></label>
                            <input type="text" class="form-control" name="titulo" id="busqueda" placeholder="Escribe algo" autocomplete="off">
                        </div>
                        
                        <div class="row">
                            <div class="col-md">
                                <label for="dateFrom" class="form-label mt-2"><h5>Fecha Desde</h5></label>
                                <input type="date" class="form-control" id="dateFrom" name="dateFrom"/>
                            </div>
                            <div class="col-md">
                                <label for="dateFrom" class="form-label mt-2"><h5>Fecha Hasta</h5></label>
                                <input type="date" class="form-control" id="dateTo" name="dateTo"/>
                            </div>
                        </div>

                        <div>
                            <h5 class="mt-2">Tipo de Evento</h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="form-check m-1">
                                    <input class="form-check-input" type="radio" name="tipo" id="optionsRadios1" value="1" checked="">
                                    <label class="form-check-label" for="optionsRadios1">
                                    Presencial
                                    </label>
                                </div>
                                <div class="form-check m-1">
                                    <input class="form-check-input" type="radio" name="tipo" id="optionsRadios2" value="0">
                                    <label class="form-check-label" for="optionsRadios2">
                                    Virtual
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-outline-info" name="busqueda">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
    
            <div class="row">
                <?php 
                    if ($tipo_usuario==0 or $tipo_usuario==1) {
                        echo '            
                        <div class="col-md-12 d-flex justify-content-center">
                            <a type="button" href="./control_eventoEditar.php?entrarPublicar" class="btn btn-outline-primary">
                                Crear evento
                            </a>
                        </div>
                    ';
                    }
                ?>
            </div>
            
    </section>

    <section class="container">

    <div class="row justify-content-center">

        <?php 

            if ($tipo_usuario==1 || $tipo_usuario==0){

            echo '            
                    <form action="./control_eventos.php" method="POST">

                        <div class="row">
                            <table class="table">
                                <tr class="table-dark text-center">
                                    <th scope="col">
                                        id
                                    </th>
                                    <th scope="col">título</th>
                                    <th scope="col">fecha</th>
                                    <th scope="col">detalles</th>
                                    <th scope="col">
                                        <button type="button" class="btn btn-outline-danger fa fa-trash" style="font-size:36px" data-bs-toggle="modal" data-bs-target="#borrar">
                                        </button>
                                    </th>
                                </tr>';

                                if ($id) {

                                    echo '
                                    <tr class="table-secondary text-center">
                                        <th scope="row">'.$datosE['id'].'</th>
                                        <td>'.$datosE['titulo'].'</td>
                                        <td>'.$datosE['fechaEspana'].'</td>
                                        <td>
                                            <a href="./control_eventoEditar.php?ver='.$datosE["id"].'" class="text-info">Ver más</a>
                                        </td>
                                        
                                        <td>
                                            <input class="form-check-input" type="checkbox" name="borrarIDS" value="'.$datosE["id"].'" id="flexCheckDefault">
                                        </td>
                                    </tr>
                                    ';

                                } else {

                                    $i=1;
                                    foreach ($datosE as $datoA) {
                                        echo '
                                        <tr class="table-secondary text-center">
                                            <th scope="row">'.$datoA['id'].'</th>
                                            <td>'.$datoA['titulo'].'</td>
                                            <td>'.$datoA['fechaEspana'].'</td>
                                            <td>
                                                <a href="./control_eventoEditar.php?ver='.$datoA["id"].'" class="text-info">Editar evento</a>
                                            </td>
                                            <td>
                                                <input class="form-check-input" type="checkbox" name="borrarIDS'.$i.'" value="'.$datoA["id"].'" id="flexCheckDefault">
                                            </td>
                                        </tr>
                                        ';
                                        $i++;
                                    }
                                }
                            echo '</table>
                        </div>';
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
                                            <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal" aria-label="Close">CANCELAR</button>
                                            <button type="submit" class="btn btn-outline-danger" name="borrar">CONFIRMAR BORRAR</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

                    echo '</form>';

            } else {

                foreach($datosE as $datoE) {
                    echo '
    
                    <div class="col-md-10">
                        <div class="card mb-3 text-white bg-primary">
                            <div class="foto_card" style="background-image: url(../imagenes/fotos_eventos/'.$datoE['foto_portada'].');">
                            </div>
                            <div class="card-header d-flex flex-column  align-items-center justify-content-center">
                                <h2>'.$datoE['titulo'].'</h2>';
    
                                    if ($datoE["tipo"]==1) {
                                        echo '<span class="badge rounded-pill bg-success"> Evento presencial </span>';
                                    } else {
                                        echo '<span class="badge rounded-pill bg-info"> Evento Virtual </span>';
                                    }
    
                            echo '</div>
                            <div class="card-footer d-flex flex-column align-items-center justify-content-between">
                                <span>'.$datoE['fecha'].'</span>
                                <form action="./control_eventoLeer.php" method="GET">
                                    <button type="submit" name="ver" value="'.$datoE['id'].'" class="btn btn-outline-warning"> VER MÁS </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    ';
                }

            }
    
        
        ?>
    </div>

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <ul class="pagination">
                <form action="./control_eventos.php" method="POST" class="d-flex">
                    <?php 
                        if(isset($_POST['busqueda'])) {
                            echo '                       
                                <input type="hidden" name="titulo" value='.$Titulo.'>
                                <input type="hidden" name="busqueda">
                                ';
                        }
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
                        if ($pagina_actual!=$num_paginas) {
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
    </section>

    </main>


<?php 
    pintar_footer(false);
?> 