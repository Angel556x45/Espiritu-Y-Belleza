<?php 

//FUNCIONA
    class modelo_usuario {
        private $bd;
        private $usuario;

        public function __construct() {
            $this->bd=conectar::conexion();
            $this->bd->set_charset("utf8");
            $this->usuario=array();
        }

        public function insertar_usuario ($nombre,$email,$nick,$pass) {
            $consulta=$this->bd->prepare("INSERT INTO usuario VALUES (NULL,?,?,?,?,'defecto.jpeg',1,3)");
            $consulta->bind_param("ssss",$nombre,$email,$nick,$pass);
            $consulta->execute();
            $consulta->store_result();

        }

        public function modificar_usuario_admin ($id, $nombre, $nick, $pass, $foto, $estado, $tipo) {
            $consulta = $this->bd->prepare("UPDATE usuario SET nombre=?, nick=?, pass=?, foto=?, estado=?, tipo=? WHERE id=?");
            $consulta->bind_param("sssssss",$nombre, $nick, $pass, $foto, $estado, $tipo, $id);
            $consulta->execute();
        }

        public function modificar_foto_usuario_personal ($id, $foto) {
            $consulta = $this->bd->prepare("UPDATE usuario SET foto=? WHERE id=?");
            $consulta->bind_param("ss",$foto, $id);
            $consulta->execute();
            $consulta->store_result();

            echo $consulta->affected_rows;

            if ($consulta->affected_rows!=0) {
                return true;
            } else {
                return false;
            } 
        }

        //Sirve para desactivar el usuario
        public function cambiar_estado_del_usuario ($id,$estado) {
            $consulta = $this->bd->prepare("UPDATE usuario SET estado=? WHERE id=?");
            $consulta->bind_param("ss", $estado, $id);
            $consulta->execute();
        }

        public function cambiar_tipo_del_usuario ($id,$tipo) {
            $consulta = $this->bd->prepare("UPDATE usuario SET tipo=? WHERE id=?");
            $consulta->bind_param("ss", $tipo, $id);
            $consulta->execute();
        }
 
        public function modificar_password ($id, $pass_old, $pass_new) {

            $pass_old = md5(md5(md5($pass_old)));
            $pass_new = md5(md5(md5($pass_new)));

            $consulta = $this->bd->prepare("UPDATE usuario SET pass=? WHERE id=? and pass=?");
            $consulta->bind_param("sss", $pass_new, $id, $pass_old);
            $consulta->execute();
            $consulta->store_result();

            if ($consulta->affected_rows!=0) {
                return true;
            } else {
                return false;
            } 
        }

        public function login ($nick,$pass) {

            $pass = md5(md5(md5($pass)));

            $consulta = $this->bd->prepare("SELECT id, tipo from usuario WHERE nick=? and pass=? and estado=1");
            $consulta->bind_param("ss",$nick,$pass);
            $consulta->bind_result($id,$tipo);
            $consulta->execute();
            $consulta->store_result();

            if ($consulta->affected_rows !=0) {
                $consulta->fetch();

                $this->usuario["id"]=$id;
                $this->usuario["tipo"]=$tipo;

            } else {
                $this->usuario["id"]=-1;
            }

            return $this->usuario;
        }

        
        public function buscar_usuario_por_id ($id) {

            $consulta=$this->bd->prepare("SELECT * FROM usuario WHERE id=?");
            $consulta->bind_param("s",$id);
            $consulta->bind_result($id,$nombre, $email, $nick, $pass, $foto, $estado, $tipo);
            $consulta->execute();
            $consulta->store_result();

            $usuario = $consulta->fetch();
                $this->usuario["id"]=$id;
                $this->usuario["nombre"]=$nombre;
                $this->usuario["email"]=$email;
                $this->usuario["nick"]=$nick;
                $this->usuario["pass"]=$pass;
                $this->usuario["foto"]=$foto;
                $this->usuario["estado"]=$estado;
                $this->usuario["tipo"]=$tipo;
            
            return $this->usuario;

        }

        public function buscar_usuario_por_nombre ($nombre) {
            $nombre= "%".$nombre."%";

            $consulta=$this->bd->prepare("SELECT * FROM usuario WHERE nombre like ?");
            $consulta->bind_param("s",$nombre);
            $consulta->bind_result($id,$nombre, $email, $nick, $pass, $foto, $estado, $tipo);
            $consulta->execute();
            $consulta->store_result();

            $i=0;
            while ($usuario = $consulta->fetch()) {
                $this->usuario[$i]["id"]=$id;
                $this->usuario[$i]["nombre"]=$nombre;
                $this->usuario[$i]["email"]=$email;
                $this->usuario[$i]["nick"]=$nick;
                $this->usuario[$i]["foto"]=$foto;
                $this->usuario[$i]["estado"]=$estado;
                $this->usuario[$i]["tipo"]=$tipo;
                $i++;
            }
            return $this->usuario;

        }

        public function listar_usuario_activos_menos_yo ($id_usuario) {
            $consulta=$this->bd->query("SELECT id,nombre,email,nick,foto,tipo FROM usuario WHERE estado=1 and id!=$id_usuario ORDER BY tipo ASC");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->usuario[]=$filas;
            }
            return $this->usuario;
        }

        public function listar_usuarios () {
            $consulta=$this->bd->query("SELECT id,nombre,email,nick,foto,estado,tipo FROM usuario");

            while ($filas=$consulta -> fetch_assoc()) {
                $this->usuario[]=$filas;
            }
            return $this->usuario;
        }
    }

?>