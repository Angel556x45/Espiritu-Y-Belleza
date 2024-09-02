<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);

?>

<main>
            
    <section class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <h1 class="texto-head-oscuro">Empezar discusion</h1>
            </div>
        </div>
    </section>

    <section class="container-lg">
        <form class="row flex-column align-items-center justify-content-center" action="./control_discusionEditar.php" method="POST">
            <div class="col-md-5 text-center">
                <label for="titulo" class="form-label mt-4"><h3>Titulo</h3></label>
                <input type="text" class="form-control" name='titulo' id="titulo" placeholder="Escribe algo" required>
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
                <textarea class="form-control" name="cuerpo" id="cuerpo" rows="20" required></textarea>
            </div>


            <div class="col-md text-center">
                <button type="submit" class="btn btn-outline-info" name="publicar">Publicar discusion</button>
            </div>
        </form>
    </section>

</main>

<?php 
    pintar_footer(false);
?> 