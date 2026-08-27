<?php
require_once __DIR__ . '/config.php';

ini_set('display_errors', '0');

$nombre = basename((string) ($_GET['f'] ?? ''));
$nombre = preg_replace('/[^A-Za-z0-9._-]/', '_', $nombre);
$ruta = MT_DIR . '/' . $nombre;
$real = realpath($ruta);
$baseDir = realpath(MT_DIR);

if ($nombre === '' || $real === false || $baseDir === false || strpos($real, $baseDir . DIRECTORY_SEPARATOR) !== 0 || !is_file($real)) {
    http_response_code(404);
    exit('Archivo no encontrado.');
}

header('X-Content-Type-Options: nosniff');
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $nombre . '"');
header('Content-Length: ' . filesize($real));
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Expires: 0');

while (ob_get_level()) {
    ob_end_clean();
}
readfile($real);
exit;
