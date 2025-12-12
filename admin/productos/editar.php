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
    <title>Editar Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <h1 class="text-3xl font-bold mb-6">Editar Producto</h1>

    <a href="index.php" 
       class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
       ← Volver
    </a>

    <form action="actualizar.php" method="POST" enctype="multipart/form-data" 
          class="bg-white shadow-md p-6 mt-6 rounded-lg max-w-xl">

        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

        <label class="block mb-3">
            <span class="font-semibold">Nombre:</span>
            <input type="text" name="nombre" required
                   value="<?php echo $producto['nombre']; ?>"
                   class="w-full px-3 py-2 border rounded-lg">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Descripción:</span>
            <textarea name="descripcion" rows="3"
                      class="w-full px-3 py-2 border rounded-lg"><?php echo $producto['descripcion']; ?></textarea>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Categoría:</span>
            <select name="categoria" required class="w-full px-3 py-2 border rounded-lg">

                <?php 
                $categorias = [
                    "Cafés Calientes", "Cafés Fríos", "Tés",
                    "Comidas", "Postres", "Frappes"
                ];

                foreach ($categorias as $cat) {
                    $sel = ($producto["categoria"] === $cat) ? "selected" : "";
                    echo "<option $sel>$cat</option>";
                }
                ?>

            </select>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Precio:</span>
            <input type="number" name="precio" step="0.01" required
                   value="<?php echo $producto['precio']; ?>"
                   class="w-full px-3 py-2 border rounded-lg">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Imagen Actual:</span><br>
            <img src="/korina-pos/front/imagenes/<?php echo $producto['imagen']; ?>" 
                 class="h-16 mt-2 rounded">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Subir nueva imagen (opcional):</span>
            <input type="file" name="imagen" accept="image/*"
                   class="w-full px-3 py-2 border rounded-lg">
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Disponible:</span>
            <select name="disponible" class="w-full px-3 py-2 border rounded-lg">
                <option value="1" <?php echo $producto['disponible'] ? 'selected' : ''; ?>>Sí</option>
                <option value="0" <?php echo !$producto['disponible'] ? 'selected' : ''; ?>>No</option>
            </select>
        </label>

        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 mt-4">
            Guardar Cambios
        </button>

    </form>

</body>
</html>
