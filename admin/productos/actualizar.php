<?php
session_start();
include "../../back/conexion.php";

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$categoria = $_POST["categoria"];
$precio = $_POST["precio"];
$disponible = $_POST["disponible"];

$imagenNueva = "";
$mantenerImagen = true;

if (!empty($_FILES["imagen"]["name"])) {
    $imagenNueva = basename($_FILES["imagen"]["name"]);
    $rutaDestino = "../../front/imagenes/" . $imagenNueva;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
    $mantenerImagen = false;
}

if ($mantenerImagen) {
    $sql = $conexion->prepare("
        UPDATE productos
        SET nombre=?, descripcion=?, categoria=?, precio=?, disponible=?
        WHERE id=?
    ");
    $sql->bind_param("sssdis", $nombre, $descripcion, $categoria, $precio, $disponible, $id);

} else {
    $sql = $conexion->prepare("
        UPDATE productos
        SET nombre=?, descripcion=?, categoria=?, precio=?, disponible=?, imagen=?
        WHERE id=?
    ");
    $sql->bind_param("sssdiss", $nombre, $descripcion, $categoria, $precio, $disponible, $imagenNueva, $id);
}

$sql->execute();

header("Location: index.php");
exit();
