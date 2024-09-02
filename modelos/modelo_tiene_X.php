<?php 

    class modelo_tiene_X {
        private $bd;
        private $tiene_X;

        public function __construct(){
            $this->bd=conectar::conexion();
            $this->bd->set_charset('utf8');
            $this->tiene_X=array();
        }

        public function crear_tiene_X ($lugar, $id_lugar, $id_etiqueta) {
            if ($lugar) {
                $consulta=$this->bd->prepare("INSERT INTO tiene_a VALUES (?,?)");
            } else {
                $consulta=$this->bd->prepare("INSERT INTO tiene_d VALUES (?,?)");
            }
            $consulta->bind_param("ss",$id_lugar,$id_etiqueta);
            $consulta->execute();
            $consulta->store_result();
        }

        public function borrar_tiene_X ($lugar, $id_lugar, $id_etiqueta) {
            if ($lugar) {
                $consulta=$this->bd->prepare("DELETE FROM tiene_a WHERE id_articulo=? and id_etiqueta=?");
            } else {
                $consulta=$this->bd->prepare("DELETE FROM tiene_d WHERE id_discusion=? and id_etiqueta=?");
            }
            $consulta->bind_param("ss",$id_lugar,$id_etiqueta);
            $consulta->execute();
            $consulta->store_result();
        }

        public function listar_etiquetas_de_lugar ($lugar, $id_lugar) {

            if ($lugar) {
                $consulta=$this->bd->prepare("SELECT * FROM tiene_a WHERE id_articulo=?");
            } else {
                $consulta=$this->bd->prepare("SELECT * FROM tiene_d WHERE id_discusion=?");            
            }
            $consulta->bind_param("s",$id_lugar);
            $consulta->bind_result($id_lugar, $id_etiqueta);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($tiene_X = $consulta->fetch()) {
                $this->tiene_X[$i]["id_etiqueta"]=$id_etiqueta;
                $i++;
            }
            return $this->tiene_X;
        }

        public function exsiste_tiene_a ($lugar,$id_lugar,$id_etiqueta) {

            
            if ($lugar) {
                $consulta=$this->bd->prepare("SELECT * FROM tiene_a WHERE id_articulo=? and id_etiqueta=?");
            } else {
                $consulta=$this->bd->prepare("SELECT * FROM tiene_d WHERE id_discusion=? and id_etiqueta=?");
            }
            $consulta->bind_param("ss",$id_lugar,$id_etiqueta);
            $consulta->bind_result($id_lugar, $id_etiqueta);
            $consulta->execute();
            $consulta->store_result();

            while ($tiene_X = $consulta->fetch()) {
                $this->tiene_X["id_etiqueta"]=$id_etiqueta;
            }
            return $this->tiene_X;
        }

    }


?>