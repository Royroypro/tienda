<?php
class CategoriasModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getCategorias($estado)
    {
        return $this->selectAll("SELECT * FROM categorias WHERE estado = ?", [(int) $estado]);
    }

    public function registrar($categoria, $imagen)
    {
        $sql = "INSERT INTO categorias (categoria, imagen) VALUES (?,?)";
        $array = array($categoria, $imagen);
        return $this->insertar($sql, $array);
    }
    public function verificarCategoria($categoria)
    {
        return $this->select("SELECT categoria FROM categorias WHERE categoria = ? AND estado = 1", [$categoria]);
    }

    public function eliminar($idCat)
    {
        $sql = "UPDATE categorias SET estado = ? WHERE id = ?";
        $array = array(0, $idCat);
        return $this->save($sql, $array);
    }

    public function getCatoria($idCat)
    {
        return $this->select("SELECT * FROM categorias WHERE id = ?", [(int) $idCat]);
    }

    public function modificar($categoria, $imagen, $id)
    {
        $sql = "UPDATE categorias SET categoria=?, imagen=? WHERE id = ?";
        $array = array($categoria, $imagen, $id);
        return $this->save($sql, $array);
    }
}
 
?>
