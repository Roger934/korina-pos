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
    <title>Panel Administrador - Korina</title>
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
                Panel de Administración
            </h1>
        </div>

        <!-- USUARIO + CERRAR SESIÓN -->
        <div class="flex items-center gap-4">

            <span class="text-lg font-semibold text-[#4b2e2b]">
                Administrador: <b><?php echo $_SESSION["nombre"]; ?></b>
            </span>

            <a href="../back/logout.php" 
               class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                Cerrar sesión
            </a>

        </div>
    </div>

</div>


    <!-- CONTENIDO PRINCIPAL -->
    <div class="max-w-5xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 px-4">

        <!-- GESTIÓN DE PRODUCTOS -->
        <a href="productos/index.php"
           class="bg-white shadow-md rounded-xl p-6 text-center hover:scale-105 transition block">
           
            <img src="/korina-pos/front/imagenes/productos.png" 
                 class="h-24 mx-auto mb-4" 
                 onerror="this.style.display='none'">

            <h2 class="text-xl font-bold text-[#4b2e2b] mb-2">
                Gestión de Productos
            </h2>

            <p class="text-gray-600">
                Agrega, edita y organiza los productos disponibles en la cafetería.
            </p>
        </a>

        <!-- GESTIÓN DE EMPLEADOS -->
        <a href="empleados/index.php"
           class="bg-white shadow-md rounded-xl p-6 text-center hover:scale-105 transition block">

            <img src="/korina-pos/front/imagenes/empleados.png" 
                 class="h-24 mx-auto mb-4"
                 onerror="this.style.display='none'">

            <h2 class="text-xl font-bold text-[#4b2e2b] mb-2">
                Gestión de Empleados
            </h2>

            <p class="text-gray-600">
                Administra los usuarios que pueden acceder al sistema.
            </p>
        </a>

        <!-- CORTE DE CAJA -->
        <a href="corte/index.php"
           class="bg-white shadow-md rounded-xl p-6 text-center hover:scale-105 transition block">

            <img src="/korina-pos/front/imagenes/corte.png" 
                 class="h-24 mx-auto mb-4"
                 onerror="this.style.display='none'">

            <h2 class="text-xl font-bold text-[#4b2e2b] mb-2">
                Corte de Caja
            </h2>

            <p class="text-gray-600">
                Revisa y registra los cortes de caja al finalizar un turno.
            </p>
        </a>

    </div>

</body>
</html>
