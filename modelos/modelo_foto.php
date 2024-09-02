<?php 
    //FUNCIONA
    class modelo_foto {
        private $bd;
        private $foto;

        public function __construct() {

            $this->bd=conectar::conexion();
            $this->bd->set_charset("utf8");
            $this->foto=array();

        }

        public function insertar_foto($url,$id_articulo) {
            $consulta = $this->bd->prepare("INSERT INTO foto VALUES (?,?)");
            $consulta->bind_param("ss",$url,$id_articulo);
            $consulta->execute();
        }

        public function borrar_foto($url){
            $consulta = $this->bd->prepare("DELETE FROM foto WHERE url=?");
            $consulta->bind_param("s",$url);
            $consulta->execute();
        }

        public function buscar_foto_por_articulo ($id_articulo) {
            $consulta = $this->bd->prepare("SELECT * FROM foto WHERE id_articulo=?");
            $consulta->bind_param("s",$id_articulo);
            $consulta->bind_result($url,$id_articulo);
            $consulta->execute();
            $consulta->store_result();

            $i=0;

            while ($foto = $consulta->fetch()) {
                $this->foto[$i]["url"]=$url;
                $this->foto[$i]["id_articulo"]=$id_articulo;
                $i++;
            }
            return $this->foto;
        }

    }

?>