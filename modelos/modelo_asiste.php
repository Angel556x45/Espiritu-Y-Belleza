<?php 
//FUNCIONA

    class modelo_asiste {
        private $bd;
        private $asiste;

        public function __construct() {
            $this->bd=conectar::conexion();
            $this->bd->set_charset("utf8");
            $this->asiste=array();
        }

        public function apuntarse ($id_usuario, $id_evento) {
            $consulta=$this->bd->prepare("INSERT INTO asiste VALUES (?,?)");
            $consulta->bind_param("ss",$id_usuario, $id_evento);
            $consulta->execute();
        }

        public function Desapuntarse ($id_usuario, $id_evento) {
            $consulta=$this->bd->prepare("DELETE FROM asiste WHERE id_usuario=? and id_evento=?");
            $consulta->bind_param("ss",$id_usuario, $id_evento);
            $consulta->execute();
        }


        public function mostrar_asistentes ($id_evento) {
            $consulta = $this->bd->prepare("SELECT usuario.id, usuario.nombre, usuario.foto, usuario.tipo from asiste, usuario WHERE asiste.id_usuario=usuario.id and asiste.id_evento=?");
            $consulta->bind_param("s",$id_evento);
            $consulta->bind_result($id,$nombre,$foto,$tipo);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($asiste = $consulta->fetch()) {
                $this->asiste[$i]["id"]=$id;
                $this->asiste[$i]["nombre"]=$nombre;
                $this->asiste[$i]["foto"]=$foto;
                $this->asiste[$i]["tipo"]=$tipo;
                $i++;
            }
            return $this->asiste;
        }

        public function mostar_usuario ($id_evento, $id_usuario) {
            $consulta = $this->bd->prepare("SELECT count(id_usuario) from asiste WHERE id_evento=? and id_usuario=?");
            $consulta->bind_param("ss",$id_evento,$id_usuario);
            $consulta->bind_result($resultado);
            $consulta->execute();
            $consulta->store_result();
            $consulta->fetch();
            return $resultado;
        }
        
    }

?>