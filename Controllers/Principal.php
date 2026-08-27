<?php
//Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Principal extends Controller
{
    public function __construct()
    {
        parent::__construct();
        startSecureSession();
    }
    public function index()
    {
        $data['title'] = 'Seguridad electrónica, cámaras y cercos eléctricos en Huacho';
        $data['description'] = 'Venta e instalación de cámaras de seguridad, cercos eléctricos, equipos informáticos y servicios técnicos en Huacho.';
        $data['negocio'] = $this->model->getNegocio();
        $data['categorias'] = $this->model->getCategorias();
        $data['destacados'] = $this->model->getProductosDestacados();
        $data['tops'] = $this->model->topProductos();
        $data['agotados'] = $this->model->agotadosProductos();
        $this->views->getView('home', "index", $data);
    }

    public function productos($page)
    {
        $data['title'] = 'Productos y servicios de seguridad electrónica';
        $data['description'] = 'Conoce nuestros productos, cámaras, cercos eléctricos y servicios técnicos disponibles en Huacho.';
        //paginacion
        $pagina = (empty($page)) ? 1 : $page;
        $porPagina = 8;
        $desde = ($pagina - 1) * $porPagina;
        //VERIFICAR SI EXISTE UNA BUSQUEDA
        $search = (!empty($_GET['search'])) ? strClean($_GET['search']) : '';
        $data['maximo'] = $this->model->getPrecio('MAX(precio)');
        $data['minimo'] = $this->model->getPrecio('MIN(precio)');
        $data['categorias'] = $this->model->getCategorias();
        $data['productos'] = $this->model->getProductos($search, '', $desde, $porPagina);
        $totalProducto = $this->model->getTotalProductos($search, '');
        $data['negocio'] = $this->model->getNegocio();
        $data['pagina'] = $pagina;
        $data['total'] = ceil($totalProducto['total'] / $porPagina);
        $this->views->getView('principal', "productos", $data);
    }

    public function filtro()
    {
        $categorias = $_POST['categorias'];
        $precio = explode(';', $_POST['precios']);
        $desde = $precio[0];
        $hasta = $precio[1];
        //VERIFICAR SI EXISTE UNA BUSQUEDA
        $search = (!empty($_GET['search'])) ? strClean($_GET['search']) : '';
        $data['productos'] = $this->model->getFiltroProductos($search, $categorias, $desde, $hasta);
        $data['negocio'] = $this->model->getNegocio();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function categoria($categoria)
    {
        //VERIFICAR SI EXISTE UNA BUSQUEDA
        $search = (!empty($_GET['search'])) ? strClean($_GET['search']) : '';
        $data['categoria'] = $this->model->getCategoria($categoria);
        if (empty($data['categoria'])) {
            http_response_code(404);
            header('Location: ' . BASE_URL . 'errors');
            exit;
        }
        $data['title'] = 'Productos de ' . $data['categoria']['categoria'];
        $data['description'] = 'Productos y servicios de ' . $data['categoria']['categoria'] . ' disponibles en Seguridad Electrónica Huacho.';
        $data['categorias'] = $this->model->getCategorias();
        $data['productos'] = $this->model->getProductosCategoria($search, $data['categoria']['id']);
        $data['negocio'] = $this->model->getNegocio();
        $this->views->getView('principal', "categorias", $data);
    }

    public function carrito()
    {
        $data['title'] = 'Carrito de compra por WhatsApp';
        $data['description'] = 'Revisa tu pedido y solicita una cotización directamente por WhatsApp.';
        $data['categorias'] = $this->model->getCategorias();
        $data['negocio'] = $this->model->getNegocio();
        $this->views->getView('principal', "carrito", $data);
    }

    public function order()
    {
        header('Location: ' . BASE_URL . 'principal/carrito');
        exit;
    }

    public function address()
    {
        header('Location: ' . BASE_URL . 'principal/carrito');
        exit;
    }

    public function pagos()
    {
        header('Location: ' . BASE_URL . 'principal/carrito');
        exit;
    }

    public function complete()
    {
        header('Location: ' . BASE_URL . 'principal/carrito');
        exit;
    }

    public function contactos()
    {
        $data['title'] = 'Contacto';
        $data['description'] = 'Contacta con Seguridad Electrónica Huacho para instalaciones, soporte y cotizaciones.';
        $data['negocio'] = $this->model->getNegocio();
        $data['categorias'] = $this->model->getCategorias();
        $this->views->getView('principal', "contactos", $data);
    }

    public function listaProductos()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        $total = 0.00;
        if (!empty($json)) {
            foreach ($json as $producto) {
                $id = filter_var($producto['id'] ?? null, FILTER_VALIDATE_INT);
                $cantidad = filter_var($producto['cantidad'] ?? null, FILTER_VALIDATE_INT);
                if (!$id || !$cantidad || $cantidad < 1 || $cantidad > 100) {
                    continue;
                }
                $result = $this->model->getProducto($id);
                if (empty($result) || (int) $result['estado'] !== 1) {
                    continue;
                }
                $data['id'] = $result['id'];
                $data['nombre'] = $result['nombre'];
                $data['precio'] = $result['precio'];
                $data['cantidad'] = min($cantidad, (int) $result['cantidad']);
                if ($data['cantidad'] < 1) {
                    continue;
                }
                $data['imagen'] = $result['imagen'];
                $subTotal = $data['precio'] * $data['cantidad'];
                $data['subTotal'] = number_format($subTotal, 2);
                array_push($array['productos'], $data);
                $total += $subTotal;
            }
        }
        $array['total'] = number_format($total, 2);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    //reset password
    public function recoverpw()
    {
        $data['title'] = 'Olvidate tu contraseña';
        $this->views->getView('admin', "recoverpw", $data);
    }

    public function reset($token)
    {
        $data['title'] = 'Restablecer Contraseña';
        $data['seguridad'] = $this->model->verificarToken($token);
        if (empty($token) || empty($data['seguridad']) || !hash_equals((string) $data['seguridad']['token'], (string) $token)) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->views->getView('admin', 'reset', $data);
    }

    public function enviarMail($correo)
    {
        $correo = filter_var($correo, FILTER_VALIDATE_EMAIL);
        if (!$correo) {
            echo json_encode(['msg' => 'Si el correo existe, recibirás instrucciones', 'type' => 'success']);
            exit;
        }
        $verificar = $this->model->verificarCorreo($correo, 1);
        if (!empty($verificar)) {
            $negocio = $this->model->getNegocio();
            $mail = new PHPMailer(true);
            $token = bin2hex(random_bytes(32));
            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = $negocio['host_smtp'];                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = $negocio['user_smtp'];                     //SMTP username
                $mail->Password   = $negocio['pass_smtp'];                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = $negocio['puerto_smtp'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($negocio['correo'], $negocio['nombre']);
                $mail->addAddress($correo);

                //Content
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';                                  //Set email format to HTML
                $mail->Subject = 'Restablecer Contraseña - ' . TITLE;
                $mail->Body    = 'Has pedido restablecer tu contraseña, si no has sido omite este mensaje <br />
            Para cambiar <a href="' . BASE_URL . 'principal/reset/' . rawurlencode($token) . '">CLICK AQUI</a>';

                $mail->send();

                $verificarToken = $this->model->registrarToken($token, $correo);
                if ($verificarToken == 1) {
                    $res = array('msg' => 'CORREO ENVIADO CON UN TOKEN DE SEGURIDAD', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL REGISTRAR EL TOKEN', 'type' => 'error');
                }
            } catch (Exception $e) {
                error_log('Error SMTP al restablecer contraseña: ' . $mail->ErrorInfo);
                $res = array('msg' => 'No se pudo procesar la solicitud', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'Si el correo existe, recibirás instrucciones', 'type' => 'success');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function cambiarClave()
    {
        $nueva = (string) ($_POST['nueva'] ?? '');
        $confirmar = (string) ($_POST['confirmar'] ?? '');
        $token = strClean($_POST['token']);
        if (empty($nueva) || empty($confirmar) || empty($token) || strlen($nueva) < 10) {
            $res = array('msg' => 'TODO LOS CAMPOS CON * SON REQUERIDOS', 'type' => 'warning');
        } else {
            if ($nueva != $confirmar) {
                $res = array('msg' => 'LAS CONTRASEÑAS NO COINCIDEN', 'type' => 'warning');
            } else {
                $consulta = $this->model->verificarToken($token);
                if (!empty($consulta)) {
                    $hash = password_hash($nueva, PASSWORD_DEFAULT);
                    $data = $this->model->modificarClave($hash, $token);
                    if ($data == 1) {
                        $res = array('msg' => 'CONTRASEÑA MODIFICADA', 'type' => 'success');
                    } else {
                        $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                    }
                } else {
                    $res = array('msg' => 'TOKEN ALTERADO', 'type' => 'error');
                }
            }
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function envio()
    {
        if (
            empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['direccion'])
            || empty($_POST['ciudad']) || empty($_POST['cod']) || empty($_POST['pais'])
            || empty($_POST['telefono'])
        ) {
            $res = array('msg' => 'TODO LOS CAMPOS CON * SON REQUERIDOS', 'icono' => 'warning');
        } else {
            $_SESSION['address'] = [
                'nombre' => strClean($_POST['nombre']),
                'apellido' => strClean($_POST['apellido']),
                'direccion' => strClean($_POST['direccion']),
                'ciudad' => strClean($_POST['ciudad']),
                'cod' => strClean($_POST['cod']),
                'pais' => strClean($_POST['pais']),
                'telefono' => strClean($_POST['telefono'])
            ];
            $res = array('msg' => 'ok', 'icono' => 'success');
        }
        echo json_encode($res);
        die();
    }

    //LOGIN
    public function login()
    {
        $data['title'] = 'Tu carrito';
        $data['negocio'] = $this->model->getNegocio();
        $data['categorias'] = $this->model->getCategorias();
        $this->views->getView('principal', "login", $data);
    }
}
