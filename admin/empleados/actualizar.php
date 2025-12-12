<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$usuario = $_POST["usuario"];
$password = $_POST["password"]; // puede mantenerse igual
$rol = $_POST["rol"];

// Actualizar datos
$sql = $conexion->prepare("
    UPDATE usuarios 
    SET nombre=?, usuario=?, password=?, rol=? 
    WHERE id=?
");

$sql->bind_param("ssssi", $nombre, $usuario, $password, $rol, $id);
$sql->execute();

header("Location: index.php");
exit();
