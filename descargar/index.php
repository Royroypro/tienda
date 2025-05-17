<?php
if (isset($_GET['download'])) {
    $file = 'Sehcontrol_Installer.exe';
    if (file_exists($file)) {
        header('X-Content-Type-Options: nosniff');
        // Configuración de cabeceras para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "El archivo no existe.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Descargar Sehcontrol.exe</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 600px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            text-align: center;
        }
        h1 {
            color: #444;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .download-btn {
            display: inline-block;
            background: #008CBA;
            color: #fff;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .download-btn:hover {
            background: #005f73;
        }
        .app-info {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Descargar Sehcontrol</h1>
        <p>
            Sechcontrol.exe es una aplicación de control remoto personalizada, hecho en rust y flutter diseñada para ofrecer una experiencia de conexión remota segura y eficiente.
        </p>
        <a href="?download=true" class="download-btn">Descargar</a>
        <div class="app-info">
            <p>Versión: 1.0.0</p>
            <p>Desarrollado por: SEGURIDAD ELECTRONICA HUACHO</p>
        </div>
    </div>
</body>
</html>
