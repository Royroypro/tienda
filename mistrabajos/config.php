<?php
// Contraseña para subir/eliminar archivos. Cambia este hash con:
//   php -r 'echo password_hash("TU_NUEVA_CLAVE", PASSWORD_DEFAULT), "\n";'
// Clave por defecto: cambiame123
define('MT_PASSWORD_HASH', '$2y$10$SxnHB/jTVZymD.9wvEqes.NdvIycqp7Rbwm8OJJOnlhBeiQA6V472');

// Carpeta donde se guardan los archivos
define('MT_DIR', __DIR__ . '/archivos');

// Tamaño máximo por archivo (bytes)
define('MT_MAX_SIZE', 50 * 1024 * 1024); // 50 MB (ver .user.ini y client_max_body_size en nginx)

// Extensiones bloqueadas (ejecutables del servidor)
define('MT_BLOCKED_EXT', 'php,php3,php4,php5,php7,phtml,phar,pl,py,cgi,sh,htaccess,user.ini');
