<?php 

    require_once "./funciones.php";

    $array_usuario=comprueba_usuario();

    $id_usuario = $array_usuario[0];
    $tipo_usuario = $array_usuario[1];

    cerrar_sesion(true);

    pintar_menu(true,$tipo_usuario,$id_usuario);

?>

  <main>

    <section class="container-fluid paralax d-flex justify-content-center align-items-center p-5">

        <div class="row text-center d-flex justify-content-center">
            <figure class="text-center col-md-7 header-difuso rounded p-2">
                <blockquote class="texto-head-claro">
                    <h1 class="texto-head-claro">ESPIRITU Y BELLEZA</h1>
                  <p class="mb-0">Los cielos cuentan la gloria de Dios. 
                    El firmamento proclama la obra de sus manos</p>
                
                </blockquote>
                <figcaption class="texto-head-claro">
                  <cite title="Source Title" >-Salmo 19:1</cite>
                </figcaption>
            </figure>
        </div>
        
    </section>

    <section class="container-lg border-top border-bottom border-dark mt-3">
      <div class="row m-3">
          <div class="col-md-5 d-flex flex-column justify-content-center">
              <h2>¿Quienes somos?</h3>
              <p>
              Espiritu y belleza es un refugio digital para los que buscan expresar y apreciar el arte influenciado por la fe católica. 
              Una plataforma que también tiene una comunidad donde artistas y amantes del arte pueden encontrar inspiración e información sobre pinturas hasta arquitectura, 
              </p>
              <p>
              Esta web busca resaltar la conexión entre la <strong>fe y el arte </strong> en todas sus formas. 
              </p>
          </div>

          <div class="col-md-7"> 
              <img src="./imagenes/fotos_estilo/trinity.jpg" class="img-fluid border">
          </div>
      </div>
    </section>

    <section class="container-fluid mt-3">
      <div class="row text-center">
        <h2>Ultimos eventos</h3>

        <?php 
          pintar_ultimos_3_eventos (true);
        ?>

      </div>
      
    </section>

  </main>

    <?php 

        pintar_footer(true);

    ?> 