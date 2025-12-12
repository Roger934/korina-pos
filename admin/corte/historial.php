<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

$usuario_id = $_SESSION["id"];

$sql = $conexion->prepare("
    SELECT * FROM cortes_caja 
    WHERE usuario_id = ?
    ORDER BY fecha DESC
");
$sql->bind_param("i", $usuario_id);
$sql->execute();
$cortes = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Cortes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5efe6] min-h-screen">

    <!-- HEADER -->
    <div class="bg-[#d7bfae] p-4 shadow-md">
        <div class="flex justify-between items-center">

            <div class="flex items-center gap-4">
                <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />

                <h1 class="text-3xl font-bold text-[#4b2e2b]">
                    Historial de Cortes
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
    <div class="max-w-5xl mx-auto mt-8 bg-white p-6 rounded-xl shadow-lg">

        <a href="index.php" 
           class="bg-[#4b2e2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b423f] transition">
            ← Volver
        </a>

        <table class="w-full mt-6 text-left">

            <thead>
                <tr class="bg-[#f0e7df] text-[#4b2e2b] font-bold border-b">
                    <th class="p-3">Fecha</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Efectivo</th>
                    <th class="p-3">Tarjeta</th>
                    <th class="p-3">Desde</th>
                    <th class="p-3">Hasta</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($c = $cortes->fetch_assoc()) { ?>
                <tr class="border-b hover:bg-[#faf4ed] transition">
                    <td class="p-3"><?php echo $c["fecha"]; ?></td>
                    <td class="p-3">$<?php echo number_format($c["total_ventas"], 2); ?></td>
                    <td class="p-3">$<?php echo number_format($c["total_efectivo"], 2); ?></td>
                    <td class="p-3">$<?php echo number_format($c["total_tarjeta"], 2); ?></td>
                    <td class="p-3"><?php echo $c["ventas_desde"]; ?></td>
                    <td class="p-3"><?php echo $c["ventas_hasta"]; ?></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

</body>
</html>
