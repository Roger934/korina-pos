<?php
include "../conexion.php";

header("Content-Type: application/json");

$categoria = $_GET["categoria"] ?? "";

$sql = $conexion->prepare("
    SELECT id, nombre, precio, imagen
    FROM productos
    WHERE categoria = ? AND disponible = 1
");
$sql->bind_param("s", $categoria);
$sql->execute();

$result = $sql->get_result();
$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
?>
