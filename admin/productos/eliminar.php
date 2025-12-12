<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

include "../../back/conexion.php";

$id = $_GET["id"];

// Primero obtener la imagen para poder borrarla
$consulta = $conexion->prepare("SELECT imagen FROM productos WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$res = $consulta->get_result()->fetch_assoc();

if ($res) {
    $imagen = $res["imagen"];

    // Eliminar archivo si existe
    $ruta = "../../front/imagenes/" . $imagen;
    if (file_exists($ruta)) {
        unlink($ruta);
    }
}

// Eliminar registro
$sql = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();

header("Location: index.php");
exit();
