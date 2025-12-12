<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar empleado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6">Agregar empleado</h1>

<a href="index.php" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
    ← Volver
</a>

<form action="guardar.php" method="POST" class="bg-white max-w-xl mt-6 p-6 rounded-lg shadow-md">

    <label class="block mb-3">
        <span class="font-semibold">Nombre:</span>
        <input type="text" name="nombre" required class="w-full px-3 py-2 border rounded-lg">
    </label>

    <label class="block mb-3">
        <span class="font-semibold">Usuario:</span>
        <input type="text" name="usuario" required class="w-full px-3 py-2 border rounded-lg">
    </label>

    <label class="block mb-3">
        <span class="font-semibold">Contraseña:</span>
        <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg">
    </label>

    <label class="block mb-3">
        <span class="font-semibold">Rol:</span>
        <select name="rol" class="w-full px-3 py-2 border rounded-lg">
            <option value="admin">Administrador</option>
            <option value="cajero">Cajero</option>
        </select>
    </label>

    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 mt-4">
        Guardar empleado
    </button>

</form>

</body>
</html>
