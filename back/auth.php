<?php
session_start();
include "conexion.php";

$usuario = $_POST["usuario"] ?? "";
$password = $_POST["password"] ?? "";

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $row = $resultado->fetch_assoc();

    // Contraseña SIN encriptar por ahora
    if ($row["password"] == $password) {

        $_SESSION["id"] = $row["id"];
        $_SESSION["nombre"] = $row["nombre"];
        $_SESSION["rol"] = $row["rol"];

        if ($row["rol"] == "admin") {
            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            header("Location: ../front/cajero/pos.php");
            exit;
        }
    }
}

echo "Usuario o contraseña incorrectos";
?>
