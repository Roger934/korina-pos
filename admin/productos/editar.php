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

$sql = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - Korina Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5efe6] min-h-screen">

    <!-- ========== HEADER CAFÉ ========== -->
    <div class="bg-[#d7bfae] p-4 shadow-md">
        <div class="flex justify-between items-center max-w-5xl mx-auto">

            <!-- Logo + Título -->
            <div class="flex items-center gap-4">
                <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />
                <h1 class="text-3xl font-bold text-[#4b2e2b]">Editar Producto</h1>
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

    <!-- ========== FORMULARIO CENTRADO ========== -->
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-xl rounded-2xl p-8">

        <!-- Botón volver -->
        <a href="index.php" 
           class="inline-block mb-6 bg-[#4b2e2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b423f] transition">
            ← Volver
        </a>

        <form action="actualizar.php" method="POST" enctype="multipart/form-data" class="space-y-5">

            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

            <!-- Nombre -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Nombre:</span>
                <input type="text" name="nombre" required
                       value="<?php echo $producto['nombre']; ?>"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </label>

            <!-- Descripción -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Descripción:</span>
                <textarea name="descripcion" rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b] outline-none"><?php 
                        echo $producto['descripcion']; 
                ?></textarea>
            </label>

            <!-- Categoría -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Categoría:</span>
                <select name="categoria"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b]">

                    <?php 
                    $categorias = [
                        "Cafés Calientes", "Cafés Fríos", "Tés",
                        "Comidas", "Postres", "Frappes"
                    ];

                    foreach ($categorias as $cat) {
                        $selected = ($producto["categoria"] === $cat) ? "selected" : "";
                        echo "<option $selected>$cat</option>";
                    }
                    ?>
                </select>
            </label>

            <!-- Precio -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Precio:</span>
                <input type="number" name="precio" step="0.01" required
                       value="<?php echo $producto['precio']; ?>"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </label>

            <!-- Imagen actual -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Imagen Actual:</span><br>
                <img src="/korina-pos/front/imagenes/<?php echo $producto['imagen']; ?>" 
                     class="h-20 mt-3 rounded shadow">
            </label>

            <!-- Subir nueva imagen -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Subir nueva imagen (opcional):</span>
                <input type="file" name="imagen" accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg bg-white focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </label>

            <!-- Disponible -->
            <label class="block">
                <span class="font-semibold text-[#4b2e2b]">Disponible:</span>
                <select name="disponible"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4b2e2b]">
                    <option value="1" <?php echo $producto['disponible'] ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo !$producto['disponible'] ? 'selected' : ''; ?>>No</option>
                </select>
            </label>

            <!-- Botón guardar -->
            <button
                class="w-full bg-green-700 text-white py-3 rounded-lg font-semibold hover:bg-green-800 transition mt-4">
                Guardar Cambios
            </button>

        </form>
    </div>

</body>
</html>
