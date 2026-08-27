<?php
class Registro extends Controller
{
    public function __construct()
    {
        parent::__construct();
        startSecureSession();
    }
    public function save()
    {
        if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['clave'])) {
            if (empty($_POST['email']) || empty($_POST['email']) || empty($_POST['clave'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $nombre = strClean($_POST['nombre']);
                $tipo = 2;
                if (!$email || mb_strlen($nombre) < 2 || strlen($_POST['clave']) < 10) {
                    echo json_encode(['msg' => 'Usa un correo válido y una contraseña de al menos 10 caracteres', 'icono' => 'warning']);
                    exit;
                }
                $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
                $consulta = $this->model->getUsuario($email);
                if (empty($consulta)) {
                    $data = $this->model->registrar($email, $nombre, $clave, $tipo);
                    if ($data > 0) {
                        session_regenerate_id(true);
                        $_SESSION['id_usuario'] = $data;
                        $_SESSION['email'] = $email;
                        $_SESSION['nombre_usuario'] = $nombre;
                        $_SESSION['tipo'] = 2;
                        $respuesta = array('msg' => 'Usuario registrado', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'Error al registrarse', 'icono' => 'error');
                    }
                } else {
                    $respuesta = array('msg' => 'El correo ya existe', 'icono' => 'warning');
                }
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }
    //registrar pedidos
    public function registrarPedido()
    {
        if (!PAYPAL_ENABLED) {
            http_response_code(403);
            echo json_encode(['msg' => 'Los pagos en línea están desactivados. Realiza tu pedido por WhatsApp.', 'icono' => 'error']);
            exit;
        }
        requireLogin();
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (empty($_SESSION['address'])) {
                throw new RuntimeException('Datos de envío no encontrados');
            }

            $json = json_decode(file_get_contents('php://input'), true);
            if (!is_array($json) || empty($json['pedidos']['id']) || !is_array($json['productos'] ?? null)) {
                throw new RuntimeException('Datos del pedido inválidos');
            }

            $transaccion = (string) $json['pedidos']['id'];
            if (!preg_match('/^[A-Z0-9-]{8,32}$/i', $transaccion)) {
                throw new RuntimeException('Identificador de pago inválido');
            }

            $cantidades = [];
            foreach ($json['productos'] as $producto) {
                $id = filter_var($producto['id'] ?? null, FILTER_VALIDATE_INT);
                $cantidad = filter_var($producto['cantidad'] ?? null, FILTER_VALIDATE_INT);
                if (!$id || !$cantidad || $cantidad < 1 || $cantidad > 100) {
                    throw new RuntimeException('Cantidad de producto inválida');
                }
                $cantidades[$id] = ($cantidades[$id] ?? 0) + $cantidad;
            }
            if (empty($cantidades)) {
                throw new RuntimeException('El carrito está vacío');
            }

            $pago = $this->verificarOrdenPayPal($transaccion);
            $this->model->beginTransaction();
            if ($this->model->existeTransaccion($transaccion)) {
                throw new RuntimeException('Esta transacción ya fue registrada');
            }

            $items = [];
            $total = 0.0;
            foreach ($cantidades as $id => $cantidad) {
                $producto = $this->model->getProducto($id);
                if (empty($producto) || (int) $producto['estado'] !== 1) {
                    throw new RuntimeException('Uno de los productos ya no está disponible');
                }
                if ((int) $producto['cantidad'] < $cantidad) {
                    throw new RuntimeException('Stock insuficiente para ' . $producto['nombre']);
                }
                $total += (float) $producto['precio'] * $cantidad;
                $items[] = [$producto, $cantidad];
            }

            if (($pago['currency_code'] ?? '') !== 'USD' || abs((float) $pago['value'] - $total) > 0.01) {
                throw new RuntimeException('El importe pagado no coincide con el pedido');
            }

            $cliente = $_SESSION['address'];
            $ventaId = $this->model->registrarPedido(
                $transaccion,
                number_format($total, 2, '.', ''),
                $cliente['nombre'],
                $cliente['apellido'],
                $cliente['direccion'],
                $cliente['ciudad'],
                $cliente['cod'],
                $cliente['pais'],
                $cliente['telefono'],
                0,
                $_SESSION['id_usuario']
            );
            if (!$ventaId) {
                throw new RuntimeException('No se pudo registrar el pedido');
            }

            foreach ($items as [$producto, $cantidad]) {
                $this->model->registrarDetalle($producto['nombre'], $producto['precio'], $cantidad, $producto['id'], $ventaId);
                $this->model->actualizarStock(
                    (int) $producto['cantidad'] - $cantidad,
                    (int) $producto['ventas'] + $cantidad,
                    $producto['id']
                );
            }

            $this->model->commit();
            unset($_SESSION['address']);
            echo json_encode(['msg' => 'Pedido registrado', 'icono' => 'success']);
        } catch (Throwable $e) {
            $this->model->rollBack();
            error_log('Error al registrar pedido: ' . $e->getMessage());
            http_response_code(422);
            echo json_encode(['msg' => $e->getMessage(), 'icono' => 'error']);
        }
        exit;
    }

    private function verificarOrdenPayPal($orderId)
    {
        if (CLIENT_ID === '' || PAYPAL_SECRET === '') {
            throw new RuntimeException('PayPal no está configurado en el servidor');
        }

        $tokenRequest = curl_init(rtrim(PAYPAL_BASE_URL, '/') . '/v1/oauth2/token');
        curl_setopt_array($tokenRequest, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_USERPWD => CLIENT_ID . ':' . PAYPAL_SECRET,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
        ]);
        $tokenBody = curl_exec($tokenRequest);
        $tokenStatus = curl_getinfo($tokenRequest, CURLINFO_HTTP_CODE);
        curl_close($tokenRequest);
        $token = json_decode((string) $tokenBody, true);
        if ($tokenStatus !== 200 || empty($token['access_token'])) {
            throw new RuntimeException('No se pudo validar el pago con PayPal');
        }

        $orderRequest = curl_init(rtrim(PAYPAL_BASE_URL, '/') . '/v2/checkout/orders/' . rawurlencode($orderId));
        curl_setopt_array($orderRequest, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token['access_token'], 'Accept: application/json'],
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
        ]);
        $orderBody = curl_exec($orderRequest);
        $orderStatus = curl_getinfo($orderRequest, CURLINFO_HTTP_CODE);
        curl_close($orderRequest);
        $order = json_decode((string) $orderBody, true);
        if ($orderStatus !== 200 || ($order['status'] ?? '') !== 'COMPLETED') {
            throw new RuntimeException('El pago no está completado');
        }

        return $order['purchase_units'][0]['payments']['captures'][0]['amount']
            ?? $order['purchase_units'][0]['amount']
            ?? [];
    }
}
