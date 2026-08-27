<?php

require_once dirname(__DIR__) . '/Config/Config.php';

$allowedOrigin = rtrim(BASE_URL, '/');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === $allowedOrigin) {
    header('Access-Control-Allow-Origin: ' . $allowedOrigin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
if (DNI_API_TOKEN === '') {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'Servicio no configurado']);
    exit;
}

$rateFile = sys_get_temp_dir() . '/sehuacho_dni_' . hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
$now = time();
$rate = ['start' => $now, 'count' => 0];
$handle = fopen($rateFile, 'c+');
if ($handle && flock($handle, LOCK_EX)) {
    $stored = stream_get_contents($handle);
    $decoded = json_decode($stored, true);
    if (is_array($decoded) && $now - ($decoded['start'] ?? 0) < 60) {
        $rate = $decoded;
    }
    $rate['count']++;
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($rate));
    flock($handle, LOCK_UN);
    fclose($handle);
}
if ($rate['count'] > 30) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Demasiadas solicitudes']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$dni = $input['dni'] ?? '';
if (!is_string($dni) || !preg_match('/^\d{8}$/', $dni)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'DNI inválido']);
    exit;
}

$url = 'https://api.apis.net.pe/v2/reniec/dni?' . http_build_query([
    'token' => DNI_API_TOKEN,
    'numero' => $dni,
]);
$request = curl_init($url);
curl_setopt_array($request, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 10,
]);
$response = curl_exec($request);
$status = curl_getinfo($request, CURLINFO_HTTP_CODE);
curl_close($request);
$data = json_decode((string) $response, true);

if ($status !== 200 || !is_array($data)) {
    http_response_code(502);
    echo json_encode(['success' => false, 'message' => 'No se pudo consultar el DNI']);
    exit;
}

echo json_encode([
    'success' => true,
    'data' => [
        'nombres' => $data['nombres'] ?? '',
        'apellidoPaterno' => $data['apellidoPaterno'] ?? '',
        'apellidoMaterno' => $data['apellidoMaterno'] ?? '',
        'tipoDocumento' => $data['tipoDocumento'] ?? '',
        'numeroDocumento' => $data['numeroDocumento'] ?? '',
        'digitoVerificador' => $data['digitoVerificador'] ?? '',
    ],
], JSON_UNESCAPED_UNICODE);
