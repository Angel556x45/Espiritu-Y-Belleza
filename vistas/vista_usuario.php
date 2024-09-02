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
                        <p> <strong>Error</strong> Selecciona al menos un usuario </p> 
                    </div>';

            echo '</section>';
        }

        if (isset ($_GET['bien'])) {
            echo '<section class="container">';

                    echo '
                    <div class="alert alert-dismissible alert-success text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <p><strong>Perfecto</strong> los usuarios seleccionados han sido suspendidos </p> 
                    </div>
                    ';

            echo '</section>';
        }
    ?>


        <section class="container-fluid d-flex justify-content-center align-items-center p-5 ">
            <div class="row text-center d-flex justify-content-center">
                <h1 class="texto-head-oscuro">USUARIOS</h2>
            </div>
        </section>

        <section class="container border-top border-dark mt-3 mb-3">
            <div class="row d-flex justify-content-center ">
                <div class="col-md-6 text-center">
                        <div class="col-md-12 text-center">
                            <label for="busquedaID" class="form-label mt-2"><h4>Filtrar por ID</h4></label>
                            <input type="number" class="form-control" name="id" id="busquedaID" placeholder="Id del usuario" autocomplete="off">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="busqueda" class="form-label mt-2"><h4>Filtrar por nombre </h4></label>
                            <input type="text" class="form-control" name="titulo" id="busqueda" placeholder="Nombre del usuario" autocomplete="off">
                        </div>

                        <div class="col-md-12 text-center">
                            <label for="tipo" class="form-label mt-4"><h4 class="mt-2">Filtrar por rango</h4></label>
                            <select class="form-select" id="tipo">
                                <option value="-1">Cualquiera</option>
                                <option value="1">Moderadores</option>
                                <option value="2">Predicadores</option>
                                <option value="3"> Laicos</option>
                            </select>
                        </div>
                    
                        <div class="col-md-12 text-center">
                            <h5 class="mt-4">Filtrar por estado del usuario</h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="form-check m-1">
                                    <input class="form-check-input" type="radio" name="tipo" id="optionsRadios1" value="1">
                                    <label class="form-check-label" for="optionsRadios1">
                                    activo
                                    </label>
                                </div>
                                <div class="form-check m-1">
                                    <input class="form-check-input" type="radio" name="tipo" id="optionsRadios2" value="0">
                                    <label class="form-check-label" for="optionsRadios2">
                                    suspendido
                                    </label>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
    </section>

    <section class="container">

    <div class="row justify-content-center">

                    
    <form action="./control_eventos.php" method="POST">

        <div class="row">
            <table class="table">
                <tr class="table-dark text-center">
                    <th scope="col">
                        id
                    </th>
                    <th scope="col">Nombre</th>
                    <th scope="col">rango</th>
                    <th scope="col">email</th>
                    <th scope="col">nick</th>
                    <th scope="col">estado</th>
                    <th scope="col">Editar</th>
                </tr>
                <tr class="table-secondary text-center">
                    <th scope="row">21</th>
                    <td>Angel Luis Castillo Lopez</td>
                    <td>ADMIN</td>
                    <td>a@email.com</td>
                    <td>admin</td>
                    <td>
                        Activo
                    </td>
                    <td>
                        <a href="./control_usuario_ver.php?ver=21" class="fa fa-edit text-decoration-none" style="font-size:24px"></a>
                    </td>
                </tr>
                <tr class="table-primary text-center">
                    <th scope="row">21</th>
                    <td>Angel Luis Castillo Lopez</td>
                    <td>ADMIN</td>
                    <td>a@email.com</td>
                    <td>admin</td>
                    <td>
                        Suspendido
                    </td>
                    <td>
                        <a href="./control_usuario_ver.php?ver=21" class="fa fa-edit text-decoration-none" style="font-size:24px"></a>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </form>    

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
                    
                </div>
            </div>
        </div>
    </div>

    </section>

    </main>


<?php 
    pintar_footer(false);
?> 