<?php
session_start();

// Solo admin puede entrar
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

$result = $conexion->query("SELECT * FROM productos ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5efe6] min-h-screen">

    <!-- HEADER -->
<div class="bg-[#d7bfae] p-4 shadow-md">

    <div class="flex justify-between items-center">

        <!-- LOGO + TÍTULO -->
        <div class="flex items-center gap-4">
            <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />

            <h1 class="text-3xl font-bold text-[#4b2e2b]">
                Administración de Productos
            </h1>
        </div>

        <!-- BOTONES DERECHA -->
        <div class="flex items-center gap-4">

            <a href="../dashboard.php" 
               class="bg-[#4b2e2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b423f] transition">
                ← Volver al Panel
            </a>

            <a href="../../back/logout.php" 
               class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                Cerrar sesión
            </a>

        </div>

    </div>

</div>


    <!-- CONTENIDO -->
    <div class="p-6 max-w-6xl mx-auto">

        <a href="agregar.php" 
           class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
           + Agregar Producto
        </a>

        <div class="mt-6 bg-white shadow-lg p-6 rounded-xl">
            <table class="w-full text-left">

                <thead>
                    <tr class="border-b text-[#4b2e2b] font-bold">
                        <th class="py-3">ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($p = $result->fetch_assoc()) { ?>
                    <tr class="border-b hover:bg-[#faf4ed] transition">

                        <td class="py-2"><?php echo $p["id"]; ?></td>

                        <td>
                            <img src="/korina-pos/front/imagenes/<?php echo $p["imagen"]; ?>" 
                                 class="h-12 rounded shadow-sm border">
                        </td>

                        <td class="font-semibold"><?php echo $p["nombre"]; ?></td>
                        <td><?php echo $p["categoria"]; ?></td>
                        <td class="font-semibold text-[#4b2e2b]">
                            $<?php echo number_format($p["precio"], 2); ?>
                        </td>

                        <td>
                            <?php if ($p["disponible"]) { ?>
                                <span class="text-green-700 font-bold">Sí</span>
                            <?php } else { ?>
                                <span class="text-red-600 font-bold">No</span>
                            <?php } ?>
                        </td>

                        <td class="flex gap-4 py-2">
                            <a href="editar.php?id=<?php echo $p["id"]; ?>" 
                               class="text-blue-600 hover:underline font-semibold">
                                Editar
                            </a>

                            <button 
                                onclick="confirmarEliminar(<?php echo $p['id']; ?>)"
                                class="text-red-600 hover:underline font-semibold">
                                Eliminar
                            </button>
                        </td>

                    </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmarEliminar(id) {
    Swal.fire({
        title: "¿Eliminar producto?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "eliminar.php?id=" + id;
        }
    });
}
</script>
