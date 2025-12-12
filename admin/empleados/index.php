<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

$consulta = $conexion->query("SELECT * FROM usuarios");
$empleados = $consulta->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empleados - Korina</title>
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
                    Gestión de Empleados
                </h1>
            </div>

            <!-- USUARIO + LOGOUT -->
            <div class="flex items-center gap-4">

                <span class="text-lg font-semibold text-[#4b2e2b]">
                    Administrador: <b><?php echo $_SESSION["nombre"]; ?></b>
                </span>

                <a href="../../back/logout.php" 
                   class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    Cerrar sesión
                </a>
            </div>

        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="p-6 max-w-5xl mx-auto">

        <div class="flex items-center gap-4 mb-6">
            <a href="../dashboard.php" 
               class="bg-[#4b2e2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b423f] transition">
                ← Volver al Panel
            </a>

            <a href="agregar.php" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                + Agregar Empleado
            </a>
        </div>

        <!-- TABLA -->
        <div class="bg-white shadow-lg p-6 rounded-xl overflow-hidden">

            <table class="w-full text-left">

                <thead>
                    <tr class="border-b bg-[#f0e7df] text-[#4b2e2b] font-bold">
                        <th class="p-3">ID</th>
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Usuario</th>
                        <th class="p-3">Rol</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($empleados as $e): ?>
                    <tr class="border-b hover:bg-[#faf4ed] transition">

                        <td class="p-3"><?php echo $e["id"]; ?></td>
                        <td class="p-3 font-semibold"><?php echo $e["nombre"]; ?></td>
                        <td class="p-3"><?php echo $e["usuario"]; ?></td>
                        <td class="p-3 capitalize"><?php echo $e["rol"]; ?></td>

                        <td class="p-3 flex gap-4">

                            <!-- Botón Editar -->
                            <a href="editar.php?id=<?php echo $e["id"]; ?>" 
                               class="text-blue-600 hover:underline font-semibold">
                                Editar
                            </a>

                            <!-- Botón Eliminar -->
                            <?php if ($e["id"] != $_SESSION["id"]): ?>
                                <button 
                                    onclick="confirmarEliminar(<?php echo $e['id']; ?>)"
                                    class="text-red-600 hover:underline font-semibold">
                                    Eliminar
                                </button>
                            <?php else: ?>
                                <span class="text-gray-400">(tú)</span>
                            <?php endif; ?>

                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function confirmarEliminar(id) {
        Swal.fire({
            title: "¿Eliminar empleado?",
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

</body>
</html>
