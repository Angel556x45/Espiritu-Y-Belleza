<?php 
    //FUNCIONA
    class modelo_discusion {
        private $bd;
        private $discusion;

        public function __construct() {
            $this->bd=conectar::conexion();
            $this->bd->set_charset("utf8");
            $this->discusion=array();
        }

        public function insertar_discusion($titulo,$cuerpo,$fecha,$id_usuario){
            $consulta = $this->bd->prepare("INSERT INTO discusion VALUES (NULL,?,?,?,?)");
            $consulta->bind_param("ssss",$titulo,$cuerpo,$fecha,$id_usuario);
            $consulta->execute();
        }

        public function sacar_id_correspondiente_discusion () {
            $consulta=$this->bd->prepare("Select AUTO_INCREMENT FROM information_schema.tables WHERE table_schema='spirit' and table_name='discusion'");
            $consulta->bind_result($id);
            $consulta->execute();
            $consulta->store_result();
            $consulta->fetch();

            return $id;
        }

        public function modificar_discusion ($titulo,$cuerpo,$fecha,$id_usuario,$id) {
            $consulta = $this->bd->prepare("UPDATE discusion SET titulo=?, cuerpo=?, fecha=?, id_usuario=? WHERE id=?");
            $consulta->bind_param("sssss", $titulo,$cuerpo,$fecha,$id_usuario,$id);
            $consulta->execute();
        }

        public function borrar_discusion ($id) {
            //Ademas borramos todos los comentarios asociados a la discusion que hemos borrado
            $consulta = $this->bd->prepare("DELETE FROM comenta_d WHERE id_discusion=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

            $consulta = $this->bd->prepare("DELETE FROM tiene_d WHERE id_discusion=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();

            $consulta = $this->bd->prepare("DELETE FROM discusion WHERE id=?");
            $consulta->bind_param("s",$id);
            $consulta->execute();
        }

        public function buscar_discusion_por_id($id) {
            $consulta=$this->bd->prepare("SELECT * FROM discusion WHERE id=?");
            $consulta->bind_param("s",$id);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario);
            $consulta->execute();
            $consulta->store_result();

            $discusion = $consulta->fetch();
                $this->discusion["id"]=$id;
                $this->discusion["titulo"]=$titulo;
                $this->discusion["cuerpo"]=$cuerpo;
                $this->discusion["fecha"]=$fecha;
                $this->discusion["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->discusion["id_usuario"]=$id_usuario;
            return $this->discusion;
        }

        public function buscar_discusion_por_titulo($titulo, $offset){
            $titulo = "%".$titulo."%";

            $consulta = $this->bd->prepare("SELECT * FROM discusion WHERE titulo like ? ORDER BY fecha DESC LIMIT 6 OFFSET $offset");
            $consulta->bind_param("s",$titulo);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($discusion = $consulta->fetch()) {
                $this->discusion[$i]["id"]=$id;
                $this->discusion[$i]["titulo"]=$titulo;
                $this->discusion[$i]["cuerpo"]=$cuerpo;
                $this->discusion[$i]["fecha"]=$fecha;
                $this->discusion[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->discusion[$i]["id_usuario"]=$id_usuario;
                $i++;
            }
            return $this->discusion;

        }

        public function buscar_discusion_por_titulo_tipo_y_etiquetas ($titulo,$offset,$etiquetas) {

            $titulo=trim($titulo);

            //Si no escribe nada en en busquador, nos mostrara todas las discusiones
            if ($titulo=="") {
                $titulo = "%";
            } else {
                $titulo = "%".$titulo."%";
            }

            $consulta = $this->bd->prepare("SELECT discusion.* 
            FROM discusion, tiene_d
            WHERE discusion.id=tiene_d.id_discusion
                and discusion.titulo like ?
                and tiene_d.id_etiqueta=($etiquetas) ORDER BY fecha DESC
            LIMIT 6 OFFSET $offset");
            
            $consulta->bind_param("s",$titulo);
            $consulta->bind_result($id,$titulo,$cuerpo,$fecha,$id_usuario);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($discusion = $consulta->fetch()) {
                $this->discusion[$i]["id"]=$id;
                $this->discusion[$i]["titulo"]=$titulo;
                $this->discusion[$i]["cuerpo"]=$cuerpo;
                $this->discusion[$i]["fecha"]=$fecha;
                $this->discusion[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $this->discusion[$i]["id_usuario"]=$id_usuario;
                $i++;
            }
            return $this->discusion;
        }

        public function listar_discusiones() {
            $consulta=$this->bd->query("SELECT * FROM discusion");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->discusion[]=$filas;
            }
            return $this->discusion;
        }

    }
?>