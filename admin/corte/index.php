<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

function formatearFechaBonita($fechaRaw) {
    if (!$fechaRaw || $fechaRaw == "2000-01-01 00:00:00") {
        return "Sin registros";
    }

    setlocale(LC_TIME, "es_ES.UTF-8", "Spanish_Mexico");
    $ts = strtotime($fechaRaw);
    return strftime("%A %d de %B de %Y %H:%M:%S", $ts);
}

$usuario_id = $_SESSION["id"];

// Último corte
$sql = $conexion->prepare("
    SELECT fecha FROM cortes_caja 
    WHERE usuario_id = ? 
    ORDER BY fecha DESC 
    LIMIT 1
");
$sql->bind_param("i", $usuario_id);
$sql->execute();
$res = $sql->get_result()->fetch_assoc();
$ultima_fecha = $res ? $res["fecha"] : null;

// Historial completo
$sqlHist = $conexion->prepare("
    SELECT * FROM cortes_caja 
    WHERE usuario_id = ?
    ORDER BY fecha DESC
");
$sqlHist->bind_param("i", $usuario_id);
$sqlHist->execute();
$historial = $sqlHist->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Corte de Caja</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-[#f5efe6] min-h-screen">

<!-- HEADER -->
<header class="bg-[#d7bfae] shadow-md py-4">
    <div class="max-w-6xl mx-auto flex justify-between items-center px-4">

        <div class="flex items-center gap-4">
            <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />
            <h1 class="text-3xl font-extrabold text-[#4b2e2b] tracking-wide">
                Corte de Caja
            </h1>
        </div>

        <div class="flex items-center gap-6">
            <span class="text-lg font-semibold text-[#4b2e2b]">
                Admin: <b><?php echo $_SESSION["nombre"]; ?></b>
            </span>

            <a href="../../back/logout.php"
               class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 shadow-md transition">
                Cerrar sesión
            </a>
        </div>

    </div>
</header>


<!-- CONTENIDO PRINCIPAL -->
<main class="max-w-5xl mx-auto mt-14 space-y-10">

    <!-- PANEL DE INFORMACIÓN DEL CORTE -->
    <section class="bg-white p-8 rounded-2xl shadow-xl border border-[#e5d3c5]">

        <h2 class="text-3xl font-bold text-[#4b2e2b] mb-6">Estado del Turno</h2>

        <p class="text-xl mb-4">
            Último corte realizado:
            <span class="font-bold text-[#4b2e2b]">
                <?php echo formatearFechaBonita($ultima_fecha); ?>
            </span>
        </p>

        <p class="text-gray-700 leading-relaxed">
            Todas las ventas después de este punto se incluirán en el siguiente corte.
        </p>

        <!-- Botones -->
        <div class="mt-8 flex gap-4">

            <!-- Corte -->
            <form action="procesar.php" method="POST" class="w-full">
                <button
                    class="w-full bg-gradient-to-r from-[#4b2e2b] to-[#6b423f] text-white py-4 rounded-xl text-xl font-semibold shadow-md hover:scale-105 hover:brightness-110 transition">
                    Realizar Corte de Caja
                </button>
            </form>

            <!-- Recargar -->
            <button onclick="location.reload()"
                class="bg-[#d7bfae] text-[#4b2e2b] px-6 py-4 rounded-xl font-semibold shadow hover:bg-[#c9ad99] transition">
                Actualizar
            </button>

        </div>

        <!-- Panel inferior -->
        <a href="../dashboard.php"
            class="block text-center bg-[#4b2e2b] text-white px-5 py-3 rounded-xl mt-6 hover:bg-[#6b423f] transition shadow-md">
            ← Volver al Panel
        </a>

    </section>


    <!-- HISTORIAL DE CORTES -->
    <section class="bg-white p-8 rounded-2xl shadow-xl border border-[#e5d3c5]">

        <h2 class="text-3xl font-bold text-[#4b2e2b] mb-6">Historial de cortes</h2>

        <?php if ($historial->num_rows == 0): ?>

            <script>
                Swal.fire({
                    icon: "info",
                    title: "Sin registros",
                    text: "Todavía no has realizado ningún corte de caja.",
                    confirmButtonColor: "#6b423f"
                });
            </script>

            <p class="text-gray-600 text-lg italic">No hay cortes registrados.</p>

        <?php else: ?>

        <table class="w-full text-left">
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
                <?php while ($c = $historial->fetch_assoc()) { ?>
                <tr class="border-b hover:bg-[#faf4ed] transition">
                    <td class="p-3"><?php echo formatearFechaBonita($c["fecha"]); ?></td>
                    <td class="p-3">$<?php echo number_format($c["total_ventas"], 2); ?></td>
                    <td class="p-3">$<?php echo number_format($c["total_efectivo"], 2); ?></td>
                    <td class="p-3">$<?php echo number_format($c["total_tarjeta"], 2); ?></td>
                    <td class="p-3"><?php echo formatearFechaBonita($c["ventas_desde"]); ?></td>
                    <td class="p-3"><?php echo formatearFechaBonita($c["ventas_hasta"]); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php endif; ?>

    </section>

</main>

</body>
</html>
