<?php
require_once __DIR__ . '/config.php';

ini_set('display_errors', '0');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

session_name('MTSESS');
session_start();

if (!is_dir(MT_DIR)) {
    @mkdir(MT_DIR, 0755, true);
}

if (empty($_SESSION['mt_csrf'])) {
    $_SESSION['mt_csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['mt_csrf'];
$logged = !empty($_SESSION['mt_admin']);
$mensaje = '';
$error = '';

function mt_check_csrf(): bool
{
    return isset($_POST['csrf']) && hash_equals($_SESSION['mt_csrf'], (string) $_POST['csrf']);
}

function mt_safe_name(string $nombre): string
{
    $nombre = basename($nombre);
    $nombre = preg_replace('/[^A-Za-z0-9._-]/', '_', $nombre);
    $nombre = ltrim($nombre, '.');
    return $nombre === '' ? 'archivo' : substr($nombre, 0, 120);
}

function mt_ext_bloqueada(string $nombre): bool
{
    $bloqueadas = explode(',', MT_BLOCKED_EXT);
    $partes = array_slice(explode('.', strtolower($nombre)), 1);
    foreach ($partes as $ext) {
        if (in_array($ext, $bloqueadas, true)) {
            return true;
        }
    }
    return false;
}

function mt_formato(int $bytes): string
{
    $u = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < 3) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, $i ? 1 : 0) . ' ' . $u[$i];
}

$accion = $_POST['accion'] ?? '';

if ($accion !== '' && !mt_check_csrf()) {
    $error = 'Sesión expirada, vuelve a intentarlo.';
    $accion = '';
}

// ---- Login / logout ----
if ($accion === 'login') {
    if (password_verify((string) ($_POST['password'] ?? ''), MT_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['mt_admin'] = true;
        $logged = true;
        $mensaje = 'Sesión iniciada.';
    } else {
        $error = 'Contraseña incorrecta.';
        usleep(500000);
    }
}

if ($accion === 'logout') {
    unset($_SESSION['mt_admin']);
    $logged = false;
    $mensaje = 'Sesión cerrada.';
}

// ---- Subir ----
if ($accion === 'subir' && $logged) {
    $subidos = 0;
    $files = $_FILES['archivos'] ?? null;
    if (!$files || !is_array($files['name'])) {
        $error = 'No se seleccionó ningún archivo.';
    } else {
        foreach ($files['name'] as $i => $nombreOriginal) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $error = 'Error al subir "' . htmlspecialchars($nombreOriginal) . '" (posible límite del servidor).';
                continue;
            }
            if ($files['size'][$i] > MT_MAX_SIZE) {
                $error = 'El archivo "' . htmlspecialchars($nombreOriginal) . '" supera ' . mt_formato(MT_MAX_SIZE) . '.';
                continue;
            }
            $nombre = mt_safe_name($nombreOriginal);
            if (mt_ext_bloqueada($nombre)) {
                $error = 'Tipo de archivo no permitido: ' . htmlspecialchars($nombreOriginal);
                continue;
            }
            $destino = MT_DIR . '/' . $nombre;
            $base = pathinfo($nombre, PATHINFO_FILENAME);
            $ext = pathinfo($nombre, PATHINFO_EXTENSION);
            $n = 1;
            while (file_exists($destino)) {
                $nombre = $base . '_' . $n . ($ext !== '' ? '.' . $ext : '');
                $destino = MT_DIR . '/' . $nombre;
                $n++;
            }
            if (move_uploaded_file($files['tmp_name'][$i], $destino)) {
                @chmod($destino, 0644);
                $subidos++;
            } else {
                $error = 'No se pudo guardar "' . htmlspecialchars($nombreOriginal) . '".';
            }
        }
        if ($subidos > 0) {
            $mensaje = $subidos . ' archivo(s) subido(s) correctamente.';
        }
    }
}

// ---- Eliminar ----
if ($accion === 'eliminar' && $logged) {
    $nombre = mt_safe_name((string) ($_POST['archivo'] ?? ''));
    $ruta = MT_DIR . '/' . $nombre;
    if (is_file($ruta) && realpath($ruta) !== false && strpos(realpath($ruta), realpath(MT_DIR)) === 0) {
        unlink($ruta);
        $mensaje = 'Archivo eliminado.';
    } else {
        $error = 'El archivo no existe.';
    }
}

// ---- Listado ----
$archivos = [];
foreach (glob(MT_DIR . '/*') ?: [] as $ruta) {
    if (is_file($ruta) && basename($ruta) !== '.htaccess' && basename($ruta) !== 'index.html') {
        $archivos[] = [
            'nombre' => basename($ruta),
            'size' => filesize($ruta),
            'fecha' => filemtime($ruta),
        ];
    }
}
usort($archivos, fn($a, $b) => $b['fecha'] <=> $a['fecha']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Mis Trabajos - Sehuacho</title>
<style>
    * { box-sizing: border-box; }
    body { margin:0; font-family:'Segoe UI',Tahoma,Verdana,sans-serif; background:#f2f4f7; color:#333; }
    .wrap { max-width:900px; margin:40px auto; padding:0 16px; }
    .card { background:#fff; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); padding:24px; margin-bottom:20px; }
    h1 { margin:0 0 6px; font-size:26px; color:#222; }
    .sub { color:#777; font-size:14px; margin:0 0 20px; }
    .alert { padding:12px 14px; border-radius:6px; margin-bottom:16px; font-size:14px; }
    .ok { background:#e6f6ec; color:#1c6b38; border:1px solid #b7e3c6; }
    .bad { background:#fdecec; color:#a12626; border:1px solid #f5c2c2; }
    table { width:100%; border-collapse:collapse; }
    th, td { text-align:left; padding:10px 8px; border-bottom:1px solid #eee; font-size:14px; }
    th { color:#888; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.4px; }
    td.acc { text-align:right; white-space:nowrap; }
    a.btn, button { display:inline-block; border:0; border-radius:6px; padding:8px 14px; font-size:14px; cursor:pointer; text-decoration:none; }
    .btn-p { background:#2563eb; color:#fff; }
    .btn-d { background:#e5e7eb; color:#b91c1c; }
    .btn-g { background:#e5e7eb; color:#444; }
    input[type=file], input[type=password] { width:100%; padding:10px; border:1px solid #d5d8dd; border-radius:6px; background:#fafafa; margin-bottom:12px; }
    .row { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
    .empty { color:#888; padding:24px 0; text-align:center; }
    form.inline { display:inline; }
</style>
</head>
<body>
<div class="wrap">

    <div class="card">
        <div class="row">
            <div>
                <h1>Mis Trabajos</h1>
                <p class="sub">Archivos disponibles para descargar.</p>
            </div>
            <?php if ($logged): ?>
            <form method="post" class="inline">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <input type="hidden" name="accion" value="logout">
                <button class="btn-g" type="submit">Cerrar sesión</button>
            </form>
            <?php endif; ?>
        </div>

        <?php if ($mensaje): ?><div class="alert ok"><?= $mensaje ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert bad"><?= $error ?></div><?php endif; ?>

        <?php if ($logged): ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="accion" value="subir">
            <input type="file" name="archivos[]" multiple required>
            <button class="btn-p" type="submit">Subir archivos</button>
            <span class="sub" style="margin-left:8px">Máx. <?= mt_formato(MT_MAX_SIZE) ?> por archivo</span>
        </form>
        <?php else: ?>
        <form method="post" style="max-width:320px">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="accion" value="login">
            <input type="password" name="password" placeholder="Contraseña para subir archivos" required>
            <button class="btn-p" type="submit">Entrar</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <?php if (!$archivos): ?>
            <div class="empty">Todavía no hay archivos.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr><th>Archivo</th><th>Tamaño</th><th>Fecha</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($archivos as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['nombre']) ?></td>
                    <td><?= mt_formato($a['size']) ?></td>
                    <td><?= date('d/m/Y H:i', $a['fecha']) ?></td>
                    <td class="acc">
                        <a class="btn btn-p" href="descargar.php?f=<?= urlencode($a['nombre']) ?>">Descargar</a>
                        <?php if ($logged): ?>
                        <form method="post" class="inline" onsubmit="return confirm('¿Eliminar <?= htmlspecialchars($a['nombre'], ENT_QUOTES) ?>?')">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="archivo" value="<?= htmlspecialchars($a['nombre']) ?>">
                            <button class="btn-d" type="submit">Eliminar</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
