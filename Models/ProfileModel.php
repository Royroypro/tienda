<?php
class ProfileModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuario($correo)
    {
        return $this->select("SELECT * FROM usuarios WHERE correo = ? AND estado = 1", [$correo]);
    }
    public function getCategorias()
    {
        $sql = "SELECT * FROM categorias WHERE estado = 1";
        return $this->selectAll($sql);
    }
    public function getNegocio()
    {
        return $this->select("SELECT * FROM configuracion");
    }
    public function getPedidos($id_usuario)
    {
        return $this->selectAll("SELECT * FROM ventas WHERE id_usuario = ?", [(int) $id_usuario]);
    }
    public function getDetalle($id_venta)
    {
        return $this->selectAll("SELECT * FROM detalle_ventas WHERE id_venta = ?", [(int) $id_venta]);
    }

    public function getPedido($id, $idUsuario)
    {
        return $this->select("SELECT * FROM ventas WHERE id = ? AND id_usuario = ?", [(int) $id, (int) $idUsuario]);
    }
    
}
 
?>
