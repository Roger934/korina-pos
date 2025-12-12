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

$sql = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$empleado = $result->fetch_assoc();

if (!$empleado) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar empleado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6">Editar empleado</h1>

<a href="index.php" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
    ← Volver
</a>

<form action="actualizar.php" method="POST" class="bg-white max-w-xl mt-6 p-6 rounded-lg shadow-md">

    <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">

    <label class="block mb-3">
        <span class="font-semibold">Nombre:</span>
        <input type="text" name="nombre" required
               value="<?php echo $empleado['nombre']; ?>"
               class="w-full px-3 py-2 border rounded-lg">
    </label>

    <label class="block mb-3">
        <span class="font-semibold">Usuario:</span>
        <input type="text" name="usuario" required
               value="<?php echo $empleado['usuario']; ?>"
               class="w-full px-3 py-2 border rounded-lg">
    </label>

    <label class="block mb-3">
        <span class="font-semibold">Contraseña:</span>
        <input type="text" name="password"
               value="<?php echo $empleado['password']; ?>"
               class="w-full px-3 py-2 border rounded-lg">
        <small class="text-gray-500">* Déjala igual si no deseas cambiarla</small>
    </label>

    <label class="block mb-3">
        <span class="font-semibold">Rol:</span>
        <select name="rol" class="w-full px-3 py-2 border rounded-lg">

            <option value="admin" <?php echo $empleado["rol"] == "admin" ? "selected" : ""; ?>>
                Administrador
            </option>

            <option value="cajero" <?php echo $empleado["rol"] == "cajero" ? "selected" : ""; ?>>
                Cajero
            </option>

        </select>
    </label>

    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 mt-4">
        Guardar Cambios
    </button>

</form>

</body>
</html>
