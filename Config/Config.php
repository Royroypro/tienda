<?php

$localConfig = __DIR__ . '/Config.local.php';
if (is_file($localConfig)) {
    require_once $localConfig;
}

$environment = [
    'BASE_URL' => 'APP_BASE_URL',
    'HOST' => 'DB_HOST',
    'USER' => 'DB_USER',
    'PASS' => 'DB_PASSWORD',
    'DB' => 'DB_NAME',
    'CLIENT_ID' => 'PAYPAL_CLIENT_ID',
    'PAYPAL_SECRET' => 'PAYPAL_CLIENT_SECRET',
    'PAYPAL_BASE_URL' => 'PAYPAL_BASE_URL',
    'APP_ENV' => 'APP_ENV',
    'DNI_API_TOKEN' => 'DNI_API_TOKEN',
];

foreach ($environment as $constant => $variable) {
    if (!defined($constant)) {
        $value = getenv($variable);
        define($constant, $value === false ? '' : $value);
    }
}

defined('CHARSET') || define('CHARSET', 'charset=utf8mb4');
defined('TITLE') || define('TITLE', 'SEGURIDAD ELECTRONICA HUACHO');
defined('MONEDA') || define('MONEDA', 'S/.');
defined('APP_ENV') || define('APP_ENV', 'production');
defined('PAYPAL_BASE_URL') || define('PAYPAL_BASE_URL', 'https://api-m.paypal.com');
defined('PAYPAL_ENABLED') || define('PAYPAL_ENABLED', false);

foreach (['BASE_URL', 'HOST', 'USER', 'PASS', 'DB'] as $constant) {
    if ((string) constant($constant) === '') {
        throw new RuntimeException('Falta configurar ' . $constant);
    }
}
