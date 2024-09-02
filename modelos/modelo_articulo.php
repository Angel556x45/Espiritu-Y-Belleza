<?php 
    //FUNCIONA
    class modelo_articulo {
        private $bd;
        private $articulo;

        public function __construct() {
            $this->bd=conectar::conexion();
            $this->bd->set_charset('utf8');
            $this->articulo=array();
        }

        public function insertar_articulo($titulo,$cuerpo,$fecha,$id_usuario,$tipo,$portada) {
            $consulta = $this->bd->prepare("INSERT INTO articulo VALUES (NULL,?,?,?,?,?,?)");
            $consulta->bind_param("ssssss",$titulo,$cuerpo,$fecha,$id_usuario,$tipo,$portada);
            $consulta->execute();
        }

        //Esta funcion nos devuelve el ID que se le asignara al siguiente articulo que publiquemos
        public function sacar_id_correspondiente_articulo () {
            $consulta=$this->bd->prepare("Select AUTO_INCREMENT FROM information_schema.tables WHERE table_schema='spirit' and table_name='articulo'");
            $consulta->bind_result($id);
            $consulta->execute();
            $consulta->store_result();
            $consulta->fetch();

            return $id;
        }

        public function modificar_articulo ($titulo,$cuerpo,$id) {
            $consulta = $this->bd->prepare("UPDATE articulo SET titulo=?, cuerpo=? WHERE id=?");
            $consulta->bind_param("sss", $titulo,$cuerpo,$id);
            $consulta->execute();
        }

        public function borrar_articulo ($id) {

            //Borramos las tablas con etiquetas asocaidas a este articulo
            $consulta = $this->bd->prepare("DELETE FROM tiene_a WHERE id_articulo=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

            //Ademas borramos todos los comentarios asociados al articulo que hemos borrado
            $consulta = $this->bd->prepare("DELETE FROM comenta_a WHERE id_articulo=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

            //Borramos de favoritos el articulo
            $consulta = $this->bd->prepare("DELETE FROM favorito WHERE id_articulo=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

            //Borramos todas las imagenes asociadas al articulo que hemos borrado
            $consulta = $this->bd->prepare("DELETE FROM foto WHERE id_articulo=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

            $consulta = $this->bd->prepare("DELETE FROM articulo WHERE id=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

        }

        public function buscar_articulo_por_titulo_y_tipo ($titulo, $tipo,$offset) {

            $titulo=trim($titulo);

            //Si no escribe nada en en busquador, nos mostrara todos los articulos
            if ($titulo=="") {
                $titulo = "%";
            } else {
                $titulo = "%".$titulo."%";
            }

            $consulta = $this->bd->prepare("SELECT * FROM articulo WHERE titulo like ? and tipo=? ORDER BY fecha DESC LIMIT 6 OFFSET $offset");
            $consulta->bind_param("ss",$titulo,$tipo);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario,$tipo,$portada);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($articulo = $consulta->fetch()) {
                $this->articulo[$i]["id"]=$id;
                $this->articulo[$i]["titulo"]=$titulo;
                $this->articulo[$i]["cuerpo"]=$cuerpo;
                $this->articulo[$i]["fecha"]=$fecha;
                $this->articulo[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->articulo[$i]["id_usuario"]=$id_usuario;
                $this->articulo[$i]["tipo"]=$tipo;
                $this->articulo[$i]["portada"]=$portada;

                $i++;
            }
            return $this->articulo;
        }

        public function buscar_articulo_por_titulo_tipo_y_etiquetas ($titulo, $tipo,$offset,$etiquetas) {

            $titulo=trim($titulo);

            //Si no escribe nada en en busquador, nos mostrara todos los articulos
            if ($titulo=="") {
                $titulo = "%";
            } else {
                $titulo = "%".$titulo."%";

            }

            $consulta = $this->bd->prepare("SELECT articulo.* 
            FROM articulo, tiene_a
            WHERE articulo.id=tiene_a.id_articulo 
                and articulo.titulo like ?
                and articulo.tipo=? 
                and tiene_a.id_etiqueta=($etiquetas) ORDER BY fecha DESC
            LIMIT 6 OFFSET $offset");
            
            $consulta->bind_param("ss",$titulo,$tipo);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario,$tipo,$portada);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($articulo = $consulta->fetch()) {
                $this->articulo[$i]["id"]=$id;
                $this->articulo[$i]["titulo"]=$titulo;
                $this->articulo[$i]["cuerpo"]=$cuerpo;
                $this->articulo[$i]["fecha"]=$fecha;
                $this->articulo[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->articulo[$i]["id_usuario"]=$id_usuario;
                $this->articulo[$i]["tipo"]=$tipo;
                $this->articulo[$i]["portada"]=$portada;

                $i++;
            }
            return $this->articulo;
        }

        public function buscar_articulo_por_fecha ($fecha,$orden, $offset) {

            //la variable orden siempre sera ASC o DESC para no tener que hacer dos funciones diferentes
            //para buscar de mayor a menor y viceversa con el tema de las fechas
            $consulta = $this->bd->prepare("SELECT * FROM articulo ORDER BY ? $orden OFFSET $offset");
            $consulta->bind_param("s",$fecha);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario,$tipo,$portada);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($articulo = $consulta->fetch()) {
                $this->articulo[$i]["id"]=$id;
                $this->articulo[$i]["titulo"]=$titulo;
                $this->articulo[$i]["cuerpo"]=$cuerpo;
                $this->articulo[$i]["fecha"]=$fecha;
                $this->articulo[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->articulo[$i]["id_usuario"]=$id_usuario;
                $this->articulo[$i]["tipo"]=$tipo;
                $this->articulo[$i]["portada"]=$portada;
                $i++;
            }
            return $this->articulo;
        }

        public function buscar_articulo_por_id($id) {
            $consulta=$this->bd->prepare("SELECT * FROM articulo WHERE id=?");
            $consulta->bind_param("s",$id);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario,$tipo,$portada);
            $consulta->execute();
            $consulta->store_result();

            $articulo = $consulta->fetch();
                $this->articulo["id"]=$id;
                $this->articulo["titulo"]=$titulo;
                $this->articulo["cuerpo"]=$cuerpo;
                $this->articulo["fecha"]=$fecha;
                $this->articulo["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->articulo["id_usuario"]=$id_usuario;
                $this->articulo["tipo"]=$tipo;
                $this->articulo["portada"]=$portada;
                
            return $this->articulo;

        }

        public function mostrar_ultimos_3_articulos($id_articulo) {

            $consulta=$this->bd->query("SELECT * FROM articulo WHERE id!=$id_articulo ORDER BY fecha DESC LIMIT 3");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->articulo[]=$filas;
            }
            return $this->articulo;

        }

        public function añadir_a_favorito ($id_articulo, $id_usuario) {
            $consulta = $this->bd->prepare("INSERT INTO favorito VALUES (?,?)");
            $consulta->bind_param("ss", $id_usuario, $id_articulo);
            $consulta->execute();
        }

        public function borrar_de_favorito ($id_articulo, $id_usuario) {
            $consulta = $this->bd->prepare("DELETE FROM favorito WHERE id_usuario=? and id_articulo=?");
            $consulta->bind_param("ss", $id_usuario, $id_articulo);
            $consulta->execute();
        }

        public function comprobar_favorito($id_articulo, $id_usuario) {
            $consulta=$this->bd->prepare("SELECT count(id_usuario) from favorito WHERE id_articulo=? and id_usuario=?");
            $consulta->bind_param("ss",$id_articulo, $id_usuario);
            $consulta->bind_result($resultado);
            $consulta->execute();
            $consulta->store_result();
            $consulta->fetch();
            return $resultado;
        }

        public function mostrar_articulos_favoritos ($id_usuario) {
            $consulta=$this->bd->query("SELECT articulo.*  FROM articulo, favorito WHERE articulo.id=favorito.id_articulo and favorito.id_usuario=".$id_usuario." ORDER BY fecha DESC");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->articulo[]=$filas;
            }
            return $this->articulo;
        }

        public function listar_articulos() {
            $consulta=$this->bd->query("SELECT * FROM articulo");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->articulo[]=$filas;
            }
            return $this->articulo;
        }
    }

?>