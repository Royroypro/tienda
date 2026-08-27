<?php
class ClientesModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function registrarPedido($id_transaccion, $metodo, $monto, $estado, $fecha, $email,
    $nombre, $apellido, $direccion, $ciudad, $id_cliente)
    {
        $sql = "INSERT INTO pedidos (id_transaccion, metodo, monto, estado, fecha, email,
        nombre, apellido, direccion, ciudad, id_cliente) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $datos = array($id_transaccion, $metodo, $monto, $estado, $fecha, $email,
        $nombre, $apellido, $direccion, $ciudad, $id_cliente);
        $data = $this->insertar($sql, $datos);
        if ($data > 0) {
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }

    public function registrarDetalle($producto, $precio, $cantidad, $atributos, $id_pedido, $id_producto)
    {
        $sql = "INSERT INTO detalle_pedidos (producto, precio, cantidad, atributos, id_pedido, id_producto) VALUES (?,?,?,?,?,?)";
        $datos = array($producto, $precio, $cantidad, $atributos, $id_pedido, $id_producto);
        $data = $this->insertar($sql, $datos);
        if ($data > 0) {
            $res = $data;
        } else {
            $res = 0;
        }
        return $res;
    }
    
    public function actualizarStockProducto($stock, $ventas, $id_producto)
    {
        $sql = "UPDATE productos SET cantidad=?, ventas=? WHERE id=?";
        $datos = array($stock, $ventas, $id_producto);
        return $this->save($sql, $datos);
    }

    ##### ADMIN CLIENTES ######
    public function getClientes($tipo, $estado)
    {
        return $this->selectAll("SELECT id, correo, nombre, apellido, direccion FROM usuarios WHERE tipo = ? AND estado = ?", [(int) $tipo, (int) $estado]);
    }
    public function registrar($correo, $nombre, $apellido, $direccion, $tipo)
    {
        $sql = "INSERT INTO usuarios (correo, nombre, apellido, direccion, tipo) VALUES (?,?,?,?,?)";
        $array = array($correo, $nombre, $apellido, $direccion, $tipo);
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

    public function eliminar($estado, $idCliente)
    {
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $array = array($estado, $idCliente);
        return $this->save($sql, $array);
    }
    public function editar($idCliente)
    {
        return $this->select("SELECT * FROM usuarios WHERE id = ?", [(int) $idCliente]);
    }

    public function modificar($correo, $nombre, $apellido, $direccion, $id)
    {
        $sql = "UPDATE usuarios SET correo=?, nombre=?, apellido=?, direccion=? WHERE id = ?";
        $array = array($correo, $nombre, $apellido, $direccion, $id);
        return $this->save($sql, $array);
    }
}
 
?>
