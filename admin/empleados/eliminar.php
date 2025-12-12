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

$id = $_GET["id"];

// No permitir borrar al usuario logueado
if ($id == $_SESSION["id"]) {
    header("Location: index.php?error=no-autodelete");
    exit;
}

include "../../back/conexion.php";

$sql = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();

header("Location: index.php");
exit();
