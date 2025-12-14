<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

$nombre = $_POST["nombre"];
$usuario = $_POST["usuario"];
$password = $_POST["password"]; 
$rol = $_POST["rol"];

$sql = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)");
$sql->bind_param("ssss", $nombre, $usuario, $password, $rol);
$sql->execute();

header("Location: index.php");
exit();
