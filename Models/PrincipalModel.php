<?php
class PrincipalModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getCategoria($nombre)
    {
        return $this->select("SELECT * FROM categorias WHERE categoria = ? AND estado = 1", [$nombre]);
    }
    public function getCategorias()
    {
        $sql = "SELECT * FROM categorias WHERE estado = 1";
        return $this->selectAll($sql);
    }
    public function getProductosDestacados()
    {
        $sql = "SELECT p.id, p.nombre, p.precio, p.cantidad, p.imagen, p.id_categoria, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.estado = 1 ORDER BY p.id DESC LIMIT 10";      
        return $this->selectAll($sql);
    }
    //paginacion
    public function getProductos($valor, $categoria, $desde, $porPagina)
    {
        $desde = max(0, (int) $desde);
        $porPagina = max(1, min(100, (int) $porPagina));
        $params = ['%' . $valor . '%'];
        if(is_numeric($categoria)){
            $sql = "SELECT p.id, p.nombre, p.precio, p.cantidad, p.imagen, p.id_categoria, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.nombre LIKE ? AND p.id_categoria = ? AND p.estado = 1 ORDER BY p.id DESC LIMIT $desde, $porPagina";
            $params[] = (int) $categoria;
        }else{
            $sql = "SELECT p.id, p.nombre, p.precio, p.cantidad, p.imagen, p.id_categoria, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.nombre LIKE ? AND p.estado = 1 ORDER BY p.id DESC LIMIT $desde, $porPagina";
        }        
        return $this->selectAll($sql, $params);
    }

    public function getTotalProductos($busqueda, $categoria)
    {
        if(!is_numeric($categoria)){
            if (empty($busqueda)) {
                $sql = "SELECT COUNT(*) AS total FROM productos WHERE estado = 1";
            } else {
                return $this->select("SELECT COUNT(*) AS total FROM productos WHERE nombre LIKE ? AND estado = 1", ['%' . $busqueda . '%']);
            }          
        }else{
            if (empty($busqueda)) {
                return $this->select("SELECT COUNT(*) AS total FROM productos WHERE id_categoria = ? AND estado = 1", [(int) $categoria]);
            } else {
                return $this->select("SELECT COUNT(*) AS total FROM productos WHERE nombre LIKE ? AND id_categoria = ? AND estado = 1", ['%' . $busqueda . '%', (int) $categoria]);
            }
            
        }
        return $this->select($sql);
    }

    public function getFiltroProductos($busqueda, $categorias, $desde, $hasta)
    {
        $params = ['%' . $busqueda . '%'];
        if (empty($categorias)) {
            $sql = "SELECT * FROM productos WHERE nombre LIKE ? AND precio >= ? AND precio <= ?";
            $params[] = (float) $desde;
            $params[] = (float) $hasta;
        }else{
            $ids = array_values(array_filter(array_map('intval', preg_split('/[^0-9]+/', (string) $categorias))));
            if (empty($ids)) {
                return [];
            }
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT * FROM productos WHERE nombre LIKE ? AND id_categoria IN ($placeholders) AND precio >= ? AND precio <= ?";
            $params = array_merge($params, $ids, [(float) $desde, (float) $hasta]);
        }
        return $this->selectAll($sql, $params);
    }

    public function getProductosCategoria($valor, $categoria)
    {
        if(is_numeric($categoria)){
            $sql = "SELECT p.id, p.nombre, p.precio, p.cantidad, p.imagen, p.id_categoria, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.nombre LIKE ? AND p.id_categoria = ? AND p.estado = 1 ORDER BY p.id DESC";
            return $this->selectAll($sql, ['%' . $valor . '%', (int) $categoria]);
        }else{
            $sql = "SELECT p.id, p.nombre, p.precio, p.cantidad, p.imagen, p.id_categoria, c.categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.nombre LIKE ? AND p.estado = 1 ORDER BY p.id DESC";
            return $this->selectAll($sql, ['%' . $valor . '%']);
        }  
    }

    public function getProducto($id)
    {
        return $this->select("SELECT * FROM productos WHERE id = ?", [(int) $id]);
    }

    public function topProductos()
    {
        $sql = "SELECT * FROM productos ORDER BY ventas DESC LIMIT 6";
        return $this->selectAll($sql);
    }

    public function agotadosProductos()
    {
        $sql = "SELECT * FROM productos WHERE cantidad = 0 ORDER BY RAND() DESC LIMIT 6";
        return $this->selectAll($sql);
    }

    public function getPrecio($tipo)
    {
        if (!in_array($tipo, ['MAX(precio)', 'MIN(precio)'], true)) {
            throw new InvalidArgumentException('Agregado no permitido');
        }
        $sql = "SELECT $tipo AS total FROM productos WHERE estado = 1";
        return $this->select($sql);
    }
    
    public function getNegocio()
    {
        return $this->select("SELECT * FROM configuracion");
    }

    public function verificarCorreo($correo, $tipo)
    {
        return $this->select("SELECT * FROM usuarios WHERE correo = ? AND estado = 1", [$correo]);
    }

    public function verificarToken($token)
    {
        return $this->select("SELECT * FROM usuarios WHERE token = ? AND estado = 1", [$token]);
    }

    public function registrarToken($token, $correo){
        $sql = "UPDATE usuarios SET token=? WHERE correo=?";
        return $this->save($sql, [$token, $correo]);
    }

    public function modificarClave($clave, $token){
        $sql = "UPDATE usuarios SET clave=?, token=? WHERE token=?";
        return $this->save($sql, [$clave, null, $token]);
    }

}
 
?>
