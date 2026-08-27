<?php
class ProductosModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getProductos($estado)
    {
        return $this->selectAll("SELECT p.*, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.estado = ?", [(int) $estado]);
    }
    
    public function getDatos($table)
    {
        if ($table !== 'categorias') {
            throw new InvalidArgumentException('Tabla no permitida');
        }
        return $this->selectAll("SELECT * FROM categorias WHERE estado = 1");
    }

    public function registrar($nombre, $descripcion, $precio, $cantidad, $imagen, $categoria)
    {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen, id_categoria) VALUES (?,?,?,?,?,?)";
        $array = array($nombre, $descripcion, $precio, $cantidad, $imagen, $categoria);
        return $this->insertar($sql, $array);
    }

    public function eliminar($idPro)
    {
        $sql = "UPDATE productos SET estado = ? WHERE id = ?";
        $array = array(0, $idPro);
        return $this->save($sql, $array);
    }

    public function getProducto($idPro)
    {
        return $this->select("SELECT * FROM productos WHERE id = ?", [(int) $idPro]);
    }

    public function modificar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $id)
    {
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, cantidad=?, imagen=?, id_categoria=? WHERE id = ?";
        $array = array($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $id);
        return $this->save($sql, $array);
    }

}
 
?>
