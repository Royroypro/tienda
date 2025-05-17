<?php

$nombre = $_POST["nombre"];
$mensaje = $_POST["mensaje"];

$fecha = date("Y-m-d H:i:s");

$contenido = "$nombre|$mensaje|$fecha\n";

$file = fopen("notas.txt", "a");
fwrite($file, $contenido);
fclose($file);

header("Location: notas.php");
exit;

