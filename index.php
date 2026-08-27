<?php
require_once 'Config/Config.php';
require_once 'Config/Helpers.php';

$production = APP_ENV === 'production';
ini_set('display_errors', $production ? '0' : '1');
ini_set('log_errors', '1');
error_reporting(E_ALL);
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

startSecureSession();
csrfToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
}

$requestPath = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($requestPath === '' || $requestPath === 'index.php') {
    $requestPath = 'principal/index';
}
$ruta = !empty($_GET['url']) ? $_GET['url'] : $requestPath;
$array = explode("/", $ruta);
$controller = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $array[0]));
$metodo = "index";
$parametro = "";
if (!empty($array[1])) {
    if (!empty($array[1] != "")) {
        $metodo = preg_replace('/[^a-zA-Z0-9_]/', '', $array[1]);
    }
}
if (!empty($array[2])) {
    if (!empty($array[2] != "")) {
        for ($i = 2; $i < count($array); $i++) {
            $parametro .= $array[$i] . ",";
        }
        $parametro = trim($parametro, ",");
    }
}
require_once 'Config/App/Autoload.php';
$dirControllers = "Controllers/" . $controller . ".php";
if (file_exists($dirControllers)) {
    require_once $dirControllers;
    $controller = new $controller();
    if (method_exists($controller, $metodo)) {
        $controller->$metodo($parametro);
    } else {
        header('Location: '.BASE_URL.'errors');
    }
} else {
    header('Location: ' . BASE_URL . 'errors');
}
?>
