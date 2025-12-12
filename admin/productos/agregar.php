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
    <title>Agregar Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <h1 class="text-3xl font-bold mb-6">Agregar Producto</h1>

    <a href="index.php" 
       class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
       ← Volver
    </a>

    <form action="guardar.php" method="POST" enctype="multipart/form-data" 
          class="bg-white shadow-md p-6 mt-6 rounded-lg max-w-xl">

        <label class="block mb-3">
            <span class="font-semibold">Nombre:</span>
            <input type="text" name="nombre" required
                   class="w-full px-3 py-2 border rounded-lg">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Descripción:</span>
            <textarea name="descripcion" rows="3"
                      class="w-full px-3 py-2 border rounded-lg"></textarea>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Categoría:</span>
            <select name="categoria" required
                    class="w-full px-3 py-2 border rounded-lg">

                <option value="Cafés Calientes">Cafés Calientes</option>
                <option value="Cafés Fríos">Cafés Fríos</option>
                <option value="Tés">Tés</option>
                <option value="Comidas">Comidas</option>
                <option value="Postres">Postres</option>
                <option value="Frappes">Frappes</option>

            </select>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Precio:</span>
            <input type="number" name="precio" step="0.01" required
                   class="w-full px-3 py-2 border rounded-lg">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Imagen (PNG / JPG):</span>
            <input type="file" name="imagen" accept="image/*"
                   class="w-full px-3 py-2 border rounded-lg">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Disponible:</span>
            <select name="disponible" class="w-full px-3 py-2 border rounded-lg">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </label>

        <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 mt-4">
            Guardar Producto
        </button>

    </form>

</body>
</html>
