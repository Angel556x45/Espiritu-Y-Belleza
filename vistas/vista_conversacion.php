<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espiritu y belleza</title>
    <link href="https://bootswatch.com/5/lux/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../style.css" rel="stylesheet">
</head>
<body>

<?php 
    $tipo = nombre_tipos_de_usuario($datosU['tipo']);
?>

    <main>
        <section class="container-fluid conversacion-head">
            <div class="row justify-content-start">
                <div class="col-md-2 text-center">
                    <img src="../imagenes/foto_usuario/<?php echo $datosU["foto"]; ?>" alt="avatar" width="75" height="75">
                </div>
                <div class="col-md-5 d-flex align-items-center justify-content-center">
                    <h1 class="texto-head-oscuro"><?php echo $datosU["nombre"]; ?></h1>
                </div>
                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <span class="badge <?php echo $tipo[1]; ?>"><?php echo $tipo[0]; ?></span>
                </div>
            </div>
        </section>


        <section class="<?php echo $ContainerClass; ?>" style="background-image: url(../imagenes/fotos_estilo/fondo_chat.jpg);">

            <?php 

                if (count($datosM)==0) {
                    echo '
                    <div class="row justify-content-center">
                            <div class="col-md-7">
                                <div class="card rounded">
                                    <div class="card-body">
                                        <p class="card-text text-center">
                                            Empieza la conversacion con '.$datosU["nombre"].'
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                } else {
                    foreach ($datosM as $datoM) {

                        $fecha = calcular_diferencia_fecha(date_create($datoM["fecha"]), false);
    
                        $estilo_tabla="row d-flex mt-3";
                        $estilo_card="card rounded";
    
                        if ($datoM["id_usuario_envia"] == $id_usuario) {
                            $estilo_tabla="row d-flex justify-content-end mt-3";
                            $estilo_card="card rounded text-white bg-info";
                        }
    
                        if ($datoM["leido"] == 1) {
                            $icono = "fa fa-eye";
                        } else {
                            $icono = "fa fa-low-vision";
                        }
    
                        echo '
                        <div class="'.$estilo_tabla.'">
                            <div class="col-md-5">
                                <div class="'.$estilo_card.'">
                                    <div class="card-body">
                                        <p class="card-text">
                                            '.$datoM["cuerpo"].'
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small>'.$fecha .'</small>
                                            <i style="font-size:15px" class="'.$icono.'" id="messageIcon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                }
            
            ?>

        </section>
    </main>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>