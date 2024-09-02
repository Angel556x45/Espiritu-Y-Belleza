<?php 

    //FUNCIONA
    class modelo_evento {
        private $bd;
        private $evento;

        
    public function __construct() {
        $this->bd=conectar::conexion();
        $this->bd->set_charset('utf8');
        $this->evento=array();
    }

    public function insertar_evento($titulo,$fecha,$descripcion,$tipo,$foto_portada,$id_usuario,$aforo,$lugar) {
        $consulta = $this->bd->prepare("INSERT INTO evento VALUES (NULL,?,?,?,?,?,?,?,?)");
        $consulta->bind_param("ssssssss", $titulo,$fecha,$descripcion,$tipo,$foto_portada,$id_usuario,$aforo,$lugar);
        $consulta->execute();
    }

    public function modificar_evento($titulo,$fecha,$descripcion,$tipo,$foto_portada,$id_usuario,$aforo, $lugar,$id) {
        $consulta = $this->bd->prepare("UPDATE evento SET titulo=?, fecha=?, descripcion=?, tipo=?, foto_portada=?, id_usuario=?, aforo=?, lugar=? WHERE id=?");
        $consulta->bind_param("sssssssss",$titulo,$fecha,$descripcion,$tipo,$foto_portada,$id_usuario,$aforo,$lugar,$id);
        $consulta->execute();
    }

    public function borrar_evento ($id) {
        $consulta = $this->bd->prepare("DELETE FROM asiste WHERE id_evento=?");
        $consulta->bind_param("s",$id);
        $consulta->execute();


        $consulta = $this->bd->prepare("DELETE FROM evento WHERE id=?");
        $consulta->bind_param("s",$id);
        $consulta->execute();
    }

    public function sacar_id_correspondiente_evento () {
        $consulta=$this->bd->prepare("Select AUTO_INCREMENT FROM information_schema.tables WHERE table_schema='spirit' and table_name='evento'");
        $consulta->bind_result($id);
        $consulta->execute();
        $consulta->store_result();
        $consulta->fetch();

        return $id;
    }

    public function buscar_evento_por_id($id_evento) {

        $consulta = $this->bd->prepare("SELECT * FROM evento WHERE id=?");
        $consulta->bind_param("s",$id_evento);
        $consulta->bind_result($id,$titulo,$fecha,$descripcion,$tipo,$foto_portada,$id_usuario,$aforo,$lugar);
        $consulta->execute();
        $consulta->store_result();

        while ($evento = $consulta->fetch()) {
            $this->evento["id"]=$id;
            $this->evento["titulo"]=$titulo;
            $this->evento["fecha"]=$fecha;
            $this->evento["fechaEspana"]=date("d/m/Y",strtotime($fecha));
            $this->evento["descripcion"]=$descripcion;
            $this->evento["tipo"]=$tipo;
            $this->evento["foto_portada"]=$foto_portada;
            $this->evento["id_usuario"]=$id_usuario;
            $this->evento["aforo"]=$aforo;
            $this->evento["lugar"]=$lugar;
        }
        return $this->evento;

    }

    public function buscar_evento_por_titulo($titulo, $tipo, $offset, $calendario){
        $titulo = "%".$titulo."%";

        $consulta = $this->bd->prepare("SELECT * FROM evento WHERE $calendario $tipo titulo like ? ORDER BY fecha DESC LIMIT 3 OFFSET ?");
        $consulta->bind_param("ss",$titulo, $offset);
        $consulta->bind_result($id,$titulo,$fecha,$descripcion,$tipo,$foto_portada,$id_usuario,$aforo,$lugar);
        $consulta->execute();
        $consulta->store_result();

        $i=0;
        while ($evento = $consulta->fetch()) {
            $this->evento[$i]["id"]=$id;
            $this->evento[$i]["titulo"]=$titulo;
            $this->evento[$i]["fecha"]=$fecha;
            $this->evento[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
            $this->evento[$i]["descripcion"]=$descripcion;
            $this->evento[$i]["tipo"]=$tipo;
            $this->evento[$i]["foto_portada"]=$foto_portada;
            $this->evento[$i]["id_usuario"]=$id_usuario;
            $this->evento[$i]["aforo"]=$aforo;
            $this->evento[$i]["lugar"]=$lugar;
            $i++;
        }
        return $this->evento;

    }

    public function mostrar_ultimos_3_eventos() {
        $consulta=$this->bd->query("SELECT * FROM evento ORDER BY fecha DESC LIMIT 3");

        while ($filas=$consulta -> fetch_assoc()) {
            $this->evento[]=$filas;
        }
        return $this->evento;
    }

    public function listar_eventos() {
        $consulta=$this->bd->query("SELECT * FROM evento");

        while ($filas=$consulta -> fetch_assoc()) {
            $this->evento[]=$filas;
        }
        return $this->evento;
    }

    }



?>