<?php 
    require_once "../funciones.php";
//FUNCIONA

//Este es un modelo conjunto para los comentarios de los articulos y las discusiones
//Ya que a la hora de hacer consultas solo cambia la tabla a la que hacemos las consultas

    class modelo_comentario {
        private $bd;
        private $comentario;

        public function __construct() {
            $this->bd=conectar::conexion();
            $this->bd->set_charset("utf8");
            $this->comentario=array();
        }

        //La variable lugar es un booleano que nos dice si el comentario es de articulo
        //O es de discusion para asi poner la consulta adecuada a cada caso
        public function insertar_comentario ($lugar, $id_usuario, $id_tabla, $fecha, $texto) {

            if ($lugar) {
                $consulta=$this->bd->prepare("INSERT INTO comenta_a VALUES (?,?,?,?)");
            } else {
                $consulta=$this->bd->prepare("INSERT INTO comenta_d VALUES (?,?,?,?)");
            }
            $consulta->bind_param("ssss",$id_usuario, $id_tabla, $fecha, $texto);
            $consulta->execute();
        }

        public function borrar_comentario ($lugar, $id_usuario,$id_tabla, $fecha) {

            if ($lugar) {
                $consulta=$this->bd->prepare("DELETE FROM comenta_a WHERE id_usuario=? and id_articulo=? and fecha=?");
            } else {
                $consulta=$this->bd->prepare("DELETE FROM comenta_d WHERE id_usuario=? and id_discusion=? and fecha=?");
            }
            $consulta->bind_param("sss",$id_usuario,$id_tabla, $fecha);
            $consulta->execute();
        }

        //Si la variable lugar es true buscamos los comentarios de los articulos
        //Si la variable es false buscamos los comentarios de las discusiones
        public function mostrar_comentarios ($lugar, $id_tabla) {

            if ($lugar) {
                $consulta=$this->bd->prepare("SELECT * from comenta_a WHERE id_articulo=?");
            } else {
                $consulta=$this->bd->prepare("SELECT * from comenta_d WHERE id_discusion=?");
            }
            $consulta->bind_param("s",$id_tabla);
            $consulta->bind_result($id_usuario,$id_tabla,$fecha,$texto);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($comentario = $consulta->fetch()) {
                $this->comentario[$i]["id_usuario"]=$id_usuario;
                $this->comentario[$i]["id_tabla"]=$id_tabla;
                $this->comentario[$i]["fecha"]=$fecha;
                $this->comentario[$i]["texto"]=$texto;
                $i++;
            }
            return $this->comentario;
        }

        //Sigue la misma logica que la funcion mostrar_comentarios
        //Muestra los comentarios con el nombre y la foto del usuario que los ha escrito 
        public function mostrar_comentarios_con_usuario($lugar, $admin, $id_tabla, $id_usuario, $limit, $offset) {

            if ($admin) {
                if ($lugar) {
                    $consulta=$this->bd->prepare("SELECT comenta_a.*, usuario.id, usuario.nombre, usuario.foto, usuario.tipo
                    FROM comenta_a, usuario 
                    WHERE id_articulo=? and comenta_a.id_usuario=usuario.id ORDER BY usuario.id=? DESC, fecha DESC");
                    $consulta->bind_param("ss",$id_tabla,$id_usuario);
                } else {
                    $consulta=$this->bd->prepare("SELECT comenta_d.*, usuario.id, usuario.nombre, usuario.foto, usuario.tipo
                    FROM comenta_d, usuario 
                    WHERE id_discusion=? and comenta_d.id_usuario=usuario.id ORDER BY fecha ASC");
                    $consulta->bind_param("s",$id_tabla);
                }
            } else {
                if ($lugar) {
                    $consulta=$this->bd->prepare("SELECT comenta_a.*, usuario.id, usuario.nombre, usuario.foto, usuario.tipo
                    FROM comenta_a, usuario 
                    WHERE id_articulo=? and comenta_a.id_usuario=usuario.id ORDER BY usuario.id=? DESC, fecha DESC
                    LIMIT $limit OFFSET $offset");
                    $consulta->bind_param("ss",$id_tabla,$id_usuario);
                } else {
                    $consulta=$this->bd->prepare("SELECT comenta_d.*, usuario.id, usuario.nombre, usuario.foto, usuario.tipo
                    FROM comenta_d, usuario 
                    WHERE id_discusion=? and comenta_d.id_usuario=usuario.id ORDER BY fecha ASC
                    LIMIT $limit OFFSET $offset");
                    $consulta->bind_param("s",$id_tabla);
                }
            }

            $consulta->bind_result($id_usuario, $id_tabla, $fecha, $texto, $id_usuario, $nombre_usuario, $foto_usuario, $tipo_usuario);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($comentario = $consulta->fetch()) {
                $this->comentario[$i]["id_usuario"]=$id_usuario;
                $this->comentario[$i]["id_tabla"]=$id_tabla;
                $this->comentario[$i]["fecha"]=$fecha;
                $this->comentario[$i]["fechaEspana"]=date("d/m/Y",strtotime($fecha));
                $fecha_desde = calcular_diferencia_fecha(date_create($fecha),$lugar);
                $this->comentario[$i]["fecha_desde"]=$fecha_desde;
                $this->comentario[$i]["texto"]=$texto;
                $this->comentario[$i]["id_usuario"]=$id_usuario;
                $this->comentario[$i]["nombre_usuario"]=$nombre_usuario;
                $this->comentario[$i]["foto_usuario"]=$foto_usuario;
                $this->comentario[$i]["tipo_usuario"]=$tipo_usuario;
                $i++;
            }
            return $this->comentario;
        }


        public function comprobar_comentario_exsiste ($lugar, $id_tabla, $id_usuario, $fecha) {

            if ($lugar) {
                $consulta=$this->bd->prepare("SELECT COUNT(id_usuario) from comenta_a WHERE id_articulo=? and id_usuario=? and fecha=?");
            } else {
                $consulta=$this->bd->prepare("SELECT COUNT(id_usuario) from comenta_d WHERE id_discusion=? and id_usuario=? and fecha=?");
            }
            $consulta->bind_param("sss", $id_tabla, $id_usuario, $fecha);
            $consulta->bind_result($resultado);
            $consulta->execute();
            $consulta->store_result();
            $consulta->fetch();
            return $resultado;
        }

        public function buscar_comentarios_por_usuario ($lugar, $id_usuario) {

            if ($lugar) {
                $consulta=$this->bd->prepare("SELECT * from comenta_a WHERE id_usuario=?");
            } else {
                $consulta=$this->bd->prepare("SELECT * from comenta_d WHERE id_usuario=?");
            }
            $consulta->bind_param("s",$id_usuario);
            $consulta->bind_result($id_usuario,$id_tabla,$fecha,$texto);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($comentario = $consulta->fetch()) {
                $this->comentario[$i]["id_usuario"]=$id_usuario;
                $this->comentario[$i]["id_tabla"]=$id_tabla;
                $this->comentario[$i]["fecha"]=$fecha;
                $this->comentario[$i]["texto"]=$texto;
                $i++;
            }
            return $this->comentario;
        }


    }



?>