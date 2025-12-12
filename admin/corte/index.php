<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

// Obtener fecha del último corte
$usuario_id = $_SESSION["id"];

$sql = $conexion->prepare("
    SELECT fecha 
    FROM cortes_caja 
    WHERE usuario_id = ? 
    ORDER BY fecha DESC 
    LIMIT 1
");
$sql->bind_param("i", $usuario_id);
$sql->execute();
$res = $sql->get_result()->fetch_assoc();

$ultima_fecha = $res ? $res["fecha"] : "2000-01-01 00:00:00";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Corte de Caja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-[#f5efe6] min-h-screen">

    <!-- HEADER -->
    <div class="bg-[#d7bfae] p-4 shadow-md">
        <div class="flex justify-between items-center">

            <div class="flex items-center gap-4">
                <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />

                <h1 class="text-3xl font-bold text-[#4b2e2b]">
                    Corte de Caja
                </h1>
            </div>

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
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg">

        <h2 class="text-2xl font-bold text-[#4b2e2b] mb-4">
            Información del Turno
        </h2>

        <p class="text-lg mb-2">
            Último corte realizado:  
            <b class="text-[#4b2e2b]"><?php echo $ultima_fecha; ?></b>
        </p>

        <p class="text-lg text-gray-600 mb-6">
            Todas las ventas realizadas después de esta fecha serán incluidas en el siguiente corte.
        </p>

        <form action="procesar.php" method="POST">
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold text-xl">
                Realizar Corte de Caja
            </button>
        </form>

        <a href="historial.php" class="block text-blue-600 mt-6 hover:underline font-semibold">
            Ver historial de cortes →
        </a>

        <a href="../dashboard.php" 
           class="mt-6 inline-block bg-[#4b2e2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b423f] transition">
            ← Volver al Panel
        </a>

    </div>

</body>
</html>
