<?php 
    //FUNCIONA
    class modelo_mensaje {
        private $bd;
        private $mensaje;

        public function __construct() {

            $this->bd=conectar::conexion();
            $this->bd->set_charset("utf8");
            $this->mensaje=array();

        }

        public function escribir_mensaje($cuerpo, $fecha, $leido, $id_usuario_envia, $id_usuario_recibe) {
            $consulta = $this->bd->prepare("INSERT INTO mensaje VALUES (NULL,?,?,?,?,?)");
            $consulta->bind_param("sssss", $cuerpo, $fecha, $leido, $id_usuario_envia, $id_usuario_recibe);
            $consulta->execute();
        }  

        public function mostrar_conversacion ($id_otro_usuario, $id_yo) {
            $consulta=$this->bd->query("SELECT * FROM mensaje WHERE (id_usuario_envia=$id_otro_usuario OR id_usuario_envia=$id_yo) && (id_usuario_recibe=$id_otro_usuario  OR id_usuario_recibe=$id_yo)");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->mensaje[]=$filas;
            }
            return $this->mensaje;
        }

        public function marcar_leidos ($id_otro_usuario, $id_yo) {
            $consulta = $this->bd->prepare("UPDATE mensaje SET leido=1 WHERE id_usuario_envia=? and id_usuario_recibe=?");
            $consulta->bind_param("ss",$id_otro_usuario, $id_yo);
            $consulta->execute();
        }

        public function mostrar_bandeja ($id_usuario_recibe) {
            $consulta = $this->bd->prepare("SELECT DISTINCT id_usuario_envia FROM mensaje WHERE id_usuario_recibe = ? Order by fecha DESC");
            $consulta->bind_param("s",$id_usuario_recibe);
            $consulta->bind_result($id_usuario_envia);
            $consulta->execute();
            $consulta->store_result();

            $i=0;

            while ($mensaje = $consulta->fetch()) {
                $this->mensaje[$i]["id_usuario_envia"]=$id_usuario_envia;
                $i++;
            }
            return $this->mensaje;
        }

        public function mostrar_ultimo_mensaje_recibido ($id_usuario_envia, $id_usuario_recibe) {
            $consulta = $this->bd->prepare("SELECT cuerpo, fecha, leido FROM mensaje WHERE id_usuario_envia=? and id_usuario_recibe = ?");
            $consulta->bind_param("ss",$id_usuario_envia,$id_usuario_recibe);
            $consulta->bind_result($cuerpo, $fecha, $leido);
            $consulta->execute();
            $consulta->store_result();

            while ($mensaje = $consulta->fetch()) {
                $this->mensaje["cuerpo"]=$cuerpo;
                $this->mensaje["fecha"]=$fecha;
                $this->mensaje["leido"]=$leido;
            }
            return $this->mensaje;
        }

    }

?>