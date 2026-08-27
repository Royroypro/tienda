<?php
class VentasModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getVentas($tipo)
    {
        return $this->selectAll("SELECT * FROM ventas WHERE tipo = ?", [(int) $tipo]);
    }
    public function getDetalle($id_venta)
    {
        return $this->selectAll("SELECT * FROM detalle_ventas WHERE id_venta = ?", [(int) $id_venta]);
    }

    public function buscarProducto($valor)
    {
        return $this->selectAll("SELECT id, nombre, cantidad, precio FROM productos WHERE nombre LIKE ? AND estado = 1 LIMIT 10", ['%' . $valor . '%']);
    }
    public function getProducto($id_producto)
    {
        return $this->select("SELECT * FROM productos WHERE id = ?", [(int) $id_producto]);
    }

    public function getCliente($id_cliente)
    {
        return $this->select("SELECT * FROM usuarios WHERE id = ?", [(int) $id_cliente]);
    }

    public function buscarCliente($valor)
    {
        return $this->selectAll("SELECT * FROM usuarios WHERE nombre LIKE ? AND tipo = 2 AND estado = 1 LIMIT 10", ['%' . $valor . '%']);
    }

    public function registrarVenta($total, $fecha, $cliente, $pago, $idusuario)
    {
        $sql = "INSERT INTO ventas (total, fecha, nombre, pago, tipo, id_usuario) VALUES (?,?,?,?,?,?)";
        return $this->insertar($sql, [$total, $fecha, $cliente, $pago, 2, $idusuario]);
    }

    public function registraDetalle($cantidad, $precio, $producto, $id_producto, $id_venta)
    {
        $sql = "INSERT INTO detalle_ventas (cantidad, precio, producto, id_producto, id_venta) VALUES (?,?,?,?,?)";
        return $this->insertar($sql, [$cantidad, $precio, $producto, $id_producto, $id_venta]);
    }

    public function actualizarStock($cantidad, $ventas, $idProducto)
    {
        $sql = "UPDATE productos SET cantidad = ?, ventas=? WHERE id = ?";
        return $this->save($sql, [$cantidad, $ventas, $idProducto]);
    }

    public function anular($idVenta)
    {
        $sql = "UPDATE ventas SET estado = ? WHERE id = ?";
        return $this->save($sql, [0, $idVenta]);
    }

    public function getVenta($id)
    {
        return $this->select("SELECT * FROM ventas WHERE id = ?", [(int) $id]);
    }

    public function getNegocio()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getDetalleVenta($id)
    {
        return $this->selectAll("SELECT * FROM detalle_ventas WHERE id_venta = ?", [(int) $id]);
    }
    
}
 
?>
