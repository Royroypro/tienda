<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json; charset=utf-8');

// Verificar el método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Método de solicitud no permitido o DNI no proporcionado."
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Obtener el cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);
$dni = $input['dni'] ?? '';

// Validar el DNI
if (!preg_match('/^\d{8}$/', $dni)) {
    echo json_encode([
        "success" => false,
        "message" => "DNI inválido. Debe contener exactamente 8 dígitos."
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Configuración del token y la URL
$token = "apis-token-12197.jpmoIfzbBsiprnu8yww9YBkfmOGhYXqc";
$url = "https://api.apis.net.pe/v2/reniec/dni?token=$token&numero=$dni";

// Inicializar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud
$response = curl_exec($ch);
$result = [];

if (curl_errno($ch)) {
    $result = [
        "success" => false,
        "message" => "Error en cURL: " . curl_error($ch),
    ];
} else {
    $data = json_decode($response, true);
    if ($data && json_last_error() === JSON_ERROR_NONE) {
        $result = [
            "success" => true,
            "data" => [
                "nombres" => $data['nombres'] ?? '',
                "apellidoPaterno" => $data['apellidoPaterno'] ?? '',
                "apellidoMaterno" => $data['apellidoMaterno'] ?? '',
                "tipoDocumento" => $data['tipoDocumento'] ?? '',
                "numeroDocumento" => $data['numeroDocumento'] ?? '',
                "digitoVerificador" => $data['digitoVerificador'] ?? '',
            ],
        ];
    } else {
        $result = [
            "success" => false,
            "message" => "Error al decodificar la respuesta: " . json_last_error_msg(),
        ];
    }
}

// Cerrar cURL
curl_close($ch);

// Devolver respuesta en JSON
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
