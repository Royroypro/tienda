<?php
class Pedidos extends Controller
{
    public function __construct()
    {
        // Llama al constructor de la clase padre
        parent::__construct();
        // Inicia la sesion
        session_start();
        // Verifica si la sesion esta seteada y si es administrador
        if (empty($_SESSION['tipo']) || $_SESSION['tipo'] == 2) {
            // Redirige al login de administrador
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {
        // Asigna el titulo de la pagina
        $data['title'] = 'pedidos';
        // Llama a la vista de pedidos
        $this->views->getView('admin/pedidos', "index", $data);
    }
    public function listarPedidos()
    {
        // Obtiene los pedidos con estado 1 (pendiente)
        $data = $this->model->getPedidos(1);
        // Recorre cada pedido y agrega la lista de productos
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['productos'] = '';
            $productos = $this->model->getDetalle($data[$i]['id']);
            for ($j=0; $j < count($productos); $j++) { 
                // Agrega cada producto a la lista
                $data[$i]['productos'] .= '<li class="list-group-item">'.$productos[$j]['cantidad'] . " x " . $productos[$j]['producto'] . " ------ " . $productos[$j]['precio'].'</li>';
            }
            // Verifica el estado del pedido y agrega el estado correspondiente
            if ($data[$i]['proceso'] == 1) {
                $data[$i]['estado'] =  '<span class="badge badge-warning">PENDIENTE</span>';
            } else {
                $data[$i]['estado'] =  '<span class="badge badge-success">COMPLETADO</span>';
            }
            // Agrega el boton de envio
            $data[$i]['accion'] = '<button type="button" onclick="verDireccion('.$data[$i]['id'].')" class="btn btn-primary">Envio</button>';
        }
        // Enviamos la respuesta en formato json
        echo json_encode($data);
        die();
    }
    public function update($idPedido)
    {
        // Verifica si el id es numerico
        if (is_numeric($idPedido)) {
            // Actualiza el estado del pedido a 2 (completado)
            $data = $this->model->actualizarEstado(2, $idPedido);
            if ($data == 1) {
                // Si se actualizo correctamente, enviamos un mensaje de exito
                $respuesta = array('msg' => 'Pedido actualizado', 'icono' => 'success');
            } else {
                // Si hubo un error, enviamos un mensaje de error
                $respuesta = array('msg' => 'Error al actualizar', 'icono' => 'error');
            }
            // Enviamos la respuesta en formato json
            echo json_encode($respuesta);
        }
        die();
    }

}

