<?php
class Conexion{
    private $conect;
    public function __construct()
    {
        $pdo = "mysql:host=".HOST.";dbname=".DB.";".CHARSET;
        try {
            $this->conect = new PDO($pdo, USER, PASS);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            error_log('Error de conexión a la base de datos: ' . $e->getMessage());
            throw new RuntimeException('No se pudo conectar con la base de datos');
        }
    }
    public function conect()
    {
        return $this->conect;
    }
}
 
?>
