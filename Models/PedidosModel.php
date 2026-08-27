<?php
class PedidosModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getPedidos($tipo)
    {
        return $this->selectAll("SELECT * FROM ventas WHERE tipo = ?", [(int) $tipo]);
    }
    public function actualizarEstado($proceso, $idPedido)
    {
        $sql = "UPDATE ventas SET proceso=? WHERE id = ?";
        $array = array($proceso, $idPedido);
        return $this->save($sql, $array);
    }
    public function getDetalle($id_venta)
    {
        return $this->selectAll("SELECT * FROM detalle_ventas WHERE id_venta = ?", [(int) $id_venta]);
    }
    
}
 
?>
