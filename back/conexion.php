<?php
$config = include __DIR__ . "/config.php";

$conexion = new mysqli(
    $config["DB_HOST"],
    $config["DB_USER"],
    $config["DB_PASS"],
    $config["DB_NAME"]
);

if ($conexion->connect_error) {
    die("Error de conexión a la BD: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
