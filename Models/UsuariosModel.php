<?php
class UsuariosModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuarios($tipo, $estado)
    {
        return $this->selectAll("SELECT id, correo, nombre, apellido, direccion FROM usuarios WHERE tipo = ? AND estado = ?", [(int) $tipo, (int) $estado]);
    }
    public function registrar($correo, $nombre, $apellido, $hash, $direccion, $tipo)
    {
        $sql = "INSERT INTO usuarios (correo, nombre, apellido, clave, direccion, tipo) VALUES (?,?,?,?,?,?)";
        $array = array($correo, $nombre, $apellido, $hash, $direccion, $tipo);
        return $this->insertar($sql, $array);
    }
    public function verificarCampo($item, $valor, $id)
    {
        if (!in_array($item, ['correo'], true)) {
            throw new InvalidArgumentException('Campo no permitido');
        }
        if ($id == 0) {
            $sql = "SELECT id FROM usuarios WHERE $item = ? AND $item IS NOT NULL AND estado = 1";
            $params = [$valor];
        } else {
            $sql = "SELECT id FROM usuarios WHERE $item = ? AND id != ? AND $item IS NOT NULL AND estado = 1";
            $params = [$valor, (int) $id];
        }
        return $this->select($sql, $params);
    }

    public function eliminar($idUser)
    {
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $array = array(0, $idUser);
        return $this->save($sql, $array);
    }

    public function getUsuario($idUser)
    {
        return $this->select("SELECT id, correo, nombre, apellido, direccion, clave FROM usuarios WHERE id = ?", [(int) $idUser]);
    }

    public function modificar($correo, $nombre, $apellido, $direccion, $id)
    {
        $sql = "UPDATE usuarios SET correo=?, nombre=?, apellido=?, direccion=? WHERE id = ?";
        $array = array($correo, $nombre, $apellido, $direccion, $id);
        return $this->save($sql, $array);
    }

    public function modificarClave($clave, $id)
    {
        $sql = "UPDATE usuarios SET clave=? WHERE id = ?";
        $array = array($clave, $id);
        return $this->save($sql, $array);
    }
}
 
?>
