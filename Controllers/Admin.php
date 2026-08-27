<?php
class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        startSecureSession();
    }
    public function index()
    {
        if (!empty($_SESSION['tipo']) && $_SESSION['tipo'] == 1) {
            header('Location: '. BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Acceso al sistema';
        $this->views->getView('admin', "login", $data);
    }
    
    public function validar()
    {
        $attempts = $_SESSION['admin_login_attempts'] ?? ['count' => 0, 'time' => time()];
        if (time() - $attempts['time'] > 900) {
            $attempts = ['count' => 0, 'time' => time()];
        }
        if ($attempts['count'] >= 5) {
            http_response_code(429);
            echo json_encode(['msg' => 'Demasiados intentos. Espera 15 minutos.', 'icono' => 'warning']);
            exit;
        }
        if (isset($_POST['email']) && isset($_POST['clave'])) {
            if (empty($_POST['email']) || empty($_POST['clave'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $data = $email ? $this->model->getUsuario($email) : null;
                if (empty($data)) {
                    $respuesta = array('msg' => 'Credenciales incorrectas', 'icono' => 'warning');
                } else {
                    if ($data['tipo'] == 1) {
                        if (password_verify($_POST['clave'], $data['clave'])) {
                            session_regenerate_id(true);
                            unset($_SESSION['admin_login_attempts']);
                            $_SESSION['id_usuario'] = $data['id'];
                            $_SESSION['email'] = $data['correo'];
                            $_SESSION['nombre_usuario'] = $data['nombre'];
                            $_SESSION['tipo'] = $data['tipo'];
                            $respuesta = array('msg' => 'datos correcto', 'icono' => 'success');
                        } else {
                            $respuesta = array('msg' => 'Credenciales incorrectas', 'icono' => 'warning');
                        }
                    }else{
                        $respuesta = array('msg' => 'Credenciales incorrectas', 'icono' => 'warning');
                    }
                    
                }
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        if (($respuesta['icono'] ?? '') !== 'success') {
            $attempts['count']++;
            $_SESSION['admin_login_attempts'] = $attempts;
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function home()
    {
        if (empty($_SESSION['tipo']) || $_SESSION['tipo'] == 2) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        
        $data['title'] = 'Panel Administrativo';
        $anioActual = date('Y');
        $anioAnterior = date("Y",strtotime($anioActual."- 1 year"));
        
        $data['pendientesAct'] = $this->model->getPedidos(1, $anioActual);
        $data['completadosAct'] = $this->model->getPedidos(2, $anioActual);
        
        $data['pendientesAnt'] = $this->model->getPedidos(1, $anioAnterior);
        $data['completadosAnt'] = $this->model->getPedidos(2, $anioAnterior);
        
        $this->views->getView('admin/administracion', "index", $data);
    }

    public function topProductos(){
        requireAdmin();
        $data = $this->model->topProductos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function comprasMes(){
        requireAdmin();
        $desde = date('Y') . '-01-01 00:00:00';
        $hasta = date('Y') . '-12-31 23:59:59';
        $data['pedido'] = $this->model->comprasMes($desde, $hasta);
        $data['pedido']['ene'] = $data['pedido']['ene'] == null ? 0 : $data['pedido']['ene'];       
        $data['pedido']['feb'] = $data['pedido']['feb'] == null ? 0 : $data['pedido']['feb'];       
        $data['pedido']['mar'] = $data['pedido']['mar'] == null ? 0 : $data['pedido']['mar'];       
        $data['pedido']['abr'] = $data['pedido']['abr'] == null ? 0 : $data['pedido']['abr'];       
        $data['pedido']['may'] = $data['pedido']['may'] == null ? 0 : $data['pedido']['may'];       
        $data['pedido']['jun'] = $data['pedido']['jun'] == null ? 0 : $data['pedido']['jun'];       
        $data['pedido']['jul'] = $data['pedido']['jul'] == null ? 0 : $data['pedido']['jul'];       
        $data['pedido']['ago'] = $data['pedido']['ago'] == null ? 0 : $data['pedido']['ago'];       
        $data['pedido']['sep'] = $data['pedido']['sep'] == null ? 0 : $data['pedido']['sep'];       
        $data['pedido']['oct'] = $data['pedido']['oct'] == null ? 0 : $data['pedido']['oct'];       
        $data['pedido']['nov'] = $data['pedido']['nov'] == null ? 0 : $data['pedido']['nov'];       
        $data['pedido']['dic'] = $data['pedido']['dic'] == null ? 0 : $data['pedido']['dic']; 
        
        $data['venta'] = $this->model->ventasMes($desde, $hasta);
        $data['venta']['ene'] = $data['venta']['ene'] == null ? 0 : $data['venta']['ene'];       
        $data['venta']['feb'] = $data['venta']['feb'] == null ? 0 : $data['venta']['feb'];       
        $data['venta']['mar'] = $data['venta']['mar'] == null ? 0 : $data['venta']['mar'];       
        $data['venta']['abr'] = $data['venta']['abr'] == null ? 0 : $data['venta']['abr'];       
        $data['venta']['may'] = $data['venta']['may'] == null ? 0 : $data['venta']['may'];       
        $data['venta']['jun'] = $data['venta']['jun'] == null ? 0 : $data['venta']['jun'];       
        $data['venta']['jul'] = $data['venta']['jul'] == null ? 0 : $data['venta']['jul'];       
        $data['venta']['ago'] = $data['venta']['ago'] == null ? 0 : $data['venta']['ago'];       
        $data['venta']['sep'] = $data['venta']['sep'] == null ? 0 : $data['venta']['sep'];       
        $data['venta']['oct'] = $data['venta']['oct'] == null ? 0 : $data['venta']['oct'];       
        $data['venta']['nov'] = $data['venta']['nov'] == null ? 0 : $data['venta']['nov'];       
        $data['venta']['dic'] = $data['venta']['dic'] == null ? 0 : $data['venta']['dic']; 
        
        echo json_encode($data);
        die();
    }

    public function salir()
    {
        requirePost();
        session_destroy();
        header('Location: ' . BASE_URL);
    }
}
