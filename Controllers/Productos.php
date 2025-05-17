<?php

class Productos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['tipo']) || $_SESSION['tipo'] == 2) {
            error_log("Usuario sin permisos: redirigiendo a 'admin'");
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
    }

    public function index()
    {
        error_log("Accediendo a la página de productos");
        $data['title'] = 'productos';
        $data['categorias'] = $this->model->getDatos('categorias');
        $this->views->getView('admin/productos', "index", $data);
    }

    public function listar()
    {
        error_log("Listando productos...");
        $data = $this->model->getProductos(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . BASE_URL . 'public/img/productos/' . $data[$i]['imagen'] . '" alt="' . $data[$i]['nombre'] . '" width="50">';
            $data[$i]['accion'] = '
            <a class="btn btn-info" href="#" onclick="editPro(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i> Editar</a>
            <a class="btn btn-danger" href="#" onclick="eliminarPro(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i> Eliminar</a>';
        }
        error_log("Productos listados: " . print_r($data, true));
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        error_log("Intentando registrar producto...");
        if (isset($_POST['nombre']) && isset($_POST['precio'])) {
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cantidad = (!empty($_POST['cantidad'])) ? $_POST['cantidad'] : 0;
            $descripcion = $_POST['descripcion'];
            $categoria = $_POST['categoria'];
            $fotoActual = $_POST['imagen_actual'];
            $imagen = $_FILES['imagen'];
            $tmp_name = $imagen['tmp_name'];
            $id = $_POST['id'];
            $nombreImg = date('YmdHis');

            error_log("Datos recibidos: nombre: $nombre, precio: $precio, cantidad: $cantidad");

            // Verificar si la carpeta de destino existe
            $carpetaDestino = 'public/img/productos/';
            if (!is_dir($carpetaDestino)) {
                error_log("La carpeta de destino no existe: $carpetaDestino");
                echo json_encode(array('msg' => 'La carpeta de destino no existe', 'icono' => 'error'));
                die();
            }

            // Verificar permisos de escritura en la carpeta
            if (!is_writable($carpetaDestino)) {
                error_log("La carpeta de destino no tiene permisos de escritura: $carpetaDestino");
                echo json_encode(array('msg' => 'No se tienen permisos de escritura en la carpeta de destino', 'icono' => 'error'));
                die();
            }

            if (empty($nombre) || empty($precio)) {
                error_log("Campos vacíos: nombre o precio están vacíos");
                $respuesta = array('msg' => 'todos los campos son requeridos', 'icono' => 'warning');
            } else {
                ##### VERIFICAR IMG ACTUAL #####
                $destino = null;
                if (!empty($imagen['name'])) {
                    $fecha = date('YmdHis');
                    $destino = $fecha . '.jpg';
                } else if (!empty($fotoActual) && empty($imagen['name'])) {
                    $destino = $fotoActual;
                }
                error_log("Destino de la imagen: $destino");

                ##### VERIFICAR SI EXISTE ID ######
                if (empty($id)) {
                    error_log("Registrando nuevo producto...");
                    if (empty($imagen['name'])) {
                        error_log("No se ha seleccionado una imagen");
                        $respuesta = array('msg' => 'seleccionar una imagen', 'icono' => 'error');
                    } else {
                        #### REGISTRAR PRODUCTO #####
                        $data = $this->model->registrar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria);
                        error_log("Resultado del registro: " . print_r($data, true));

                        if ($data > 0) {
                            #### GUARDAR IMAGEN #####
                            if (!empty($imagen['name'])) {
                                $destino = 'public/img/productos/' . $nombreImg . '.jpg';
                                if (move_uploaded_file($tmp_name, $destino)) {
                                    error_log("Imagen guardada en: $destino");
                                } else {
                                    error_log("Error al guardar la imagen");
                                    $respuesta = array('msg' => 'error al subir la imagen', 'icono' => 'error');
                                }
                            }
                            $respuesta = array('msg' => 'producto registrado', 'icono' => 'success');
                        } else {
                            error_log("Error al registrar el producto");
                            $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                        }
                    }
                } else {
                    error_log("Modificando producto con ID: $id");
                    ##temporal
                    $temp = $this->model->getProducto($id);
                    #### MODIFICAR PRODUCTO #####
                    $data = $this->model->modificar($nombre, $descripcion, $precio, $cantidad, $destino, $categoria, $id);
                    if ($data == 1) {
                        if (!empty($imagen['name'])) {
                            if (file_exists('public/img/productos/' . $temp['imagen'])) {
                                unlink('public/img/productos/' . $temp['imagen']);
                                error_log("Imagen antigua eliminada: " . $temp['imagen']);
                            }
                            $destino = 'public/img/productos/' . $nombreImg . '.jpg';
                            if (move_uploaded_file($tmp_name, $destino)) {
                                error_log("Imagen modificada y guardada en: $destino");
                            } else {
                                error_log("Error al subir la imagen");
                                $respuesta = array('msg' => 'error al subir la imagen', 'icono' => 'error');
                            }
                        }
                        $respuesta = array('msg' => 'producto modificado', 'icono' => 'success');
                    } else {
                        error_log("Error al modificar el producto");
                        $respuesta = array('msg' => 'error al modificar', 'icono' => 'error');
                    }
                }
            }
            error_log("Respuesta enviada: " . print_r($respuesta, true));
            echo json_encode($respuesta);
        }
        die();
    }

    //eliminar producto
    public function delete($idPro)
    {
        error_log("Eliminando producto con ID: $idPro");
        if (is_numeric($idPro)) {
            $data = $this->model->eliminar($idPro);
            if ($data == 1) {
                $respuesta = array('msg' => 'producto dado de baja', 'icono' => 'success');
            } else {
                error_log("Error al eliminar el producto");
                $respuesta = array('msg' => 'error al eliminar', 'icono' => 'error');
            }
        } else {
            error_log("ID de producto no es numérico");
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta);
        die();
    }

    // Editar producto
    public function edit($idPro)
    {
        error_log("Editando producto con ID: $idPro");
        
        // Check if the ID is numeric
        if (is_numeric($idPro)) {
            $data = $this->model->getProducto($idPro);

            // Check if the product data was retrieved successfully
            if ($data) {
                error_log("Datos del producto: " . print_r($data, true));
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                // Log error if the product is not found
                error_log("Producto no encontrado con ID: $idPro");
                echo json_encode(array('msg' => 'Producto no encontrado', 'icono' => 'error'));
            }
        } else {
            // Log error for invalid ID
            error_log("ID no válido: $idPro");
            echo json_encode(array('msg' => 'ID no válido', 'icono' => 'error'));
        }

        die();
    }
}
