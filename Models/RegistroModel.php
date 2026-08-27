<?php
class RegistroModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuario($email)
    {
        return $this->select("SELECT * FROM usuarios WHERE correo = ?", [$email]);
    }

    public function registrar($email, $nombre, $clave, $tipo) {
        $sql = "INSERT INTO usuarios (correo, nombre, clave, tipo) VALUES (?,?,?,?)";
        return $this->insertar($sql, [$email, $nombre, $clave, $tipo]);
    }

    public function getProducto($id)
    {
        return $this->select("SELECT * FROM productos WHERE id = ? FOR UPDATE", [(int) $id]);
    }

    public function registrarPedido($transaccion, $total, $nombre,
    $apellido,$direccion,$ciudad,$cod,$pais,$telefono,$envio, $id_usuario){
        $sql = "INSERT INTO ventas (transaccion, total, nombre,
        apellido,direccion,ciudad,cod,pais,telefono,envio, id_usuario) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        return $this->insertar($sql, [$transaccion, $total, $nombre,
        $apellido,$direccion,$ciudad,$cod,$pais,$telefono,$envio, $id_usuario]);
    }

    public function registrarDetalle($producto, $precio, $cantidad, $idProducto, $id_venta){
        $sql = "INSERT INTO detalle_ventas (producto, precio, cantidad,id_producto, id_venta) VALUES (?,?,?,?,?)";
        return $this->insertar($sql, [$producto, $precio, $cantidad, $idProducto, $id_venta]);
    }

    public function actualizarStock($cantidad, $ventas, $idProducto)
    {
        $sql = "UPDATE productos SET cantidad = ?, ventas=? WHERE id = ?";
        return $this->save($sql, [$cantidad, $ventas, $idProducto]);
    }

    public function existeTransaccion($transaccion)
    {
        return $this->select("SELECT id FROM ventas WHERE transaccion = ? LIMIT 1", [$transaccion]);
    }
    
}
 
?>
