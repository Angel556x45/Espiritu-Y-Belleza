<?php 
    //FUNCIONA
    class modelo_etiqueta {
        private $bd;
        private $etiqueta;

        public function __construct(){
            $this->bd=conectar::conexion();
            $this->bd->set_charset('utf8');
            $this->etiqueta=array();
        }

        public function insertar_etiqueta($nombre) {
            $consulta = $this->bd->prepare("INSERT INTO etiqueta VALUES (NULL,?)");
            $consulta->bind_param("s",$nombre);
            $consulta->execute();
        }

        public function contar_etiquetas() {
            $consulta=$this->bd->prepare("SELECT COUNT(id) FROM etiqueta ");
            $consulta->bind_result($cantidad);
            $consulta->execute();
            $consulta->store_result();
            $consulta->fetch();

            return $cantidad;
        }

        public function listar_etiquetas() {
            $consulta=$this->bd->query("SELECT * FROM etiqueta");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->etiqueta[]=$filas;
            }
            return $this->etiqueta;
        }
    }
?>