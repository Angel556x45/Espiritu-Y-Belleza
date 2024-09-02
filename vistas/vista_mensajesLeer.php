<?php 

    cerrar_sesion(false);
    pintar_menu(false,$tipo_usuario, $id_usuario);
?>

    <main>

        <section class="container-fluid" >
            <div class="row justify-content-center">
                <iframe id="mensajes" class="col-md-12 iframe-message" src="<?php echo $url_iframe ?>"></iframe>
                <div class="col-md-10 d-flex justify-content-end">
                    <button class="fa fa-refresh btn btn-outline-success" style="font-size:34px;" onclick="recargar_iframe_mensajes()">
                    </button>
                </div>  
            </div>
        </section>

        <section class="container-md mensaje-bottom">
            <form action="./control_mensajesLeer.php" method="GET" class="row">
                <div class="col-md-10 d-flex ">
                    <?php  echo '<input type="hidden" name="usuario_recibe" value="'.$_GET['entrar'].'">'; ?>
                    <textarea class="form-control" name="cuerpo" id="cuerpo" rows="3" placeholder="Escribe tu mensaje" required></textarea>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-info" name="escribir_mensaje">Enviar</button>
                </div>
            </form>
        </section>


    </main>

<?php 
    pintar_footer(false);
?> 