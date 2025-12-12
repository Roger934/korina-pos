<?php
session_start();
include "../../back/conexion.php";

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$categoria = $_POST["categoria"];
$precio = $_POST["precio"];
$disponible = $_POST["disponible"];

// Procesar imagen
$imagenNombre = "";

if (!empty($_FILES["imagen"]["name"])) {
    $imagenNombre = basename($_FILES["imagen"]["name"]);
    $rutaDestino = "../../front/imagenes/" . $imagenNombre;

    move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
}

$sql = $conexion->prepare("
INSERT INTO productos (nombre, descripcion, categoria, precio, disponible, imagen)
VALUES (?, ?, ?, ?, ?, ?)
");
$sql->bind_param("sssdis", $nombre, $descripcion, $categoria, $precio, $disponible, $imagenNombre);
$sql->execute();

header("Location: index.php");
exit();
