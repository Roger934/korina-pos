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
    <title>Agregar Producto - Korina Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5efe6] min-h-screen">

    <!-- ========== HEADER CAFÉ ========== -->
    <div class="bg-[#d7bfae] p-4 shadow-md">
        <div class="flex justify-between items-center max-w-5xl mx-auto">

            <!-- Logo + Título -->
            <div class="flex items-center gap-4">
                <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />
                <h1 class="text-3xl font-bold text-[#4b2e2b]">Agregar Producto</h1>
            </div>

            <!-- Usuario + Logout -->
            <div class="flex items-center gap-4">
                <span class="text-lg font-semibold text-[#4b2e2b]">
                    Administrador: <b><?php echo $_SESSION["nombre"]; ?></b>
                </span>

                <a href="../../back/logout.php"
                   class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>

    <!-- ========== CONTENIDO CENTRADO ========== -->
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-xl rounded-2xl p-8">

        <!-- Botón volver -->
        <a href="index.php" 
           class="inline-block mb-6 bg-[#4b2e2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b423f] transition">
            ← Volver
        </a>

        <!-- Formulario -->
        <form action="guardar.php" method="POST" enctype="multipart/form-data" class="space-y-5">

            <!-- Nombre -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Nombre:</span>
                <input type="text" name="nombre" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </label>

            <!-- Descripción -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Descripción:</span>
                <textarea name="descripcion" rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b] outline-none"></textarea>
            </label>

            <!-- Categoría -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Categoría:</span>
                <select name="categoria" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b]">

                    <option value="Cafés Calientes">Cafés Calientes</option>
                    <option value="Cafés Fríos">Cafés Fríos</option>
                    <option value="Tés">Tés</option>
                    <option value="Comidas">Comidas</option>
                    <option value="Postres">Postres</option>
                    <option value="Frappes">Frappes</option>

                </select>
            </label>

            <!-- Precio -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Precio:</span>
                <input type="number" name="precio" step="0.01" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </label>

            <!-- Imagen -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Imagen (PNG / JPG):</span>
                <input type="file" name="imagen" accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg bg-white focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </label>

            <!-- Disponible -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Disponible:</span>
                <select name="disponible" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b]">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </label>

            <!-- Botón guardar -->
            <button
                class="w-full bg-green-700 text-white py-3 rounded-lg font-semibold hover:bg-green-800 transition">
                Guardar Producto
            </button>

        </form>

    </div>

</body>
</html>
