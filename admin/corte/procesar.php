<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

include "../../back/conexion.php";

// ====================================================
// 1. OBTENER FECHA DEL ÚLTIMO CORTE (SI EXISTE)
// ====================================================
$sql = $conexion->prepare("
    SELECT fecha 
    FROM cortes_caja 
    ORDER BY fecha DESC 
    LIMIT 1
");
$sql->execute();
$res = $sql->get_result()->fetch_assoc();

// Si NO existe ningún corte → usar "2000-01-01"
$ultima_fecha = $res ? $res["fecha"] : "2000-01-01 00:00:00";


// ====================================================
// 2. OBTENER VENTAS DESPUÉS DEL ÚLTIMO CORTE
// ====================================================
$sqlVentas = $conexion->prepare("
    SELECT id, fecha, total, metodo_pago, usuario_id
    FROM ventas
    WHERE fecha > ?
    ORDER BY fecha ASC
");
$sqlVentas->bind_param("s", $ultima_fecha);
$sqlVentas->execute();
$ventas = $sqlVentas->get_result()->fetch_all(MYSQLI_ASSOC);


// ====================================================
// SI NO HAY VENTAS → mostrar alerta
// ====================================================
if (empty($ventas)) {
    echo "
    <script>
        alert('No hay ventas pendientes para corte.');
        window.location.href = 'index.php';
    </script>";
    exit;
}


// ====================================================
// 3. CALCULAR TOTALES
// ====================================================
$total_ventas   = 0;
$total_efectivo = 0;
$total_tarjeta  = 0;

$ventas_desde = $ventas[0]["fecha"];   
$ventas_hasta = end($ventas)["fecha"]; 

foreach ($ventas as $v) {
    $monto = floatval($v["total"]);
    $total_ventas += $monto;

    if ($v["metodo_pago"] === "efectivo") {
        $total_efectivo += $monto;
    }

    if ($v["metodo_pago"] === "tarjeta") {
        $total_tarjeta += $monto;
    }
}


// ====================================================
// 4. INSERTAR NUEVO CORTE EN cortes_caja
// ====================================================
$sqlInsert = $conexion->prepare("
    INSERT INTO cortes_caja (usuario_id, total_ventas, total_efectivo, total_tarjeta, ventas_desde, ventas_hasta)
    VALUES (?, ?, ?, ?, ?, ?)
");

$sqlInsert->bind_param(
    "idddss",
    $_SESSION["id"],
    $total_ventas,
    $total_efectivo,
    $total_tarjeta,
    $ventas_desde,
    $ventas_hasta
);

$sqlInsert->execute();


// ====================================================
// 5. MOSTRAR SWEET ALERT BONITO Y REGRESAR
// ====================================================
echo "
<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<title>Corte realizado</title>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>

<body>

<script>
Swal.fire({
    icon: 'success',
    title: 'Corte realizado',
    html: `
        <p><b>Total ventas:</b> $" . number_format($total_ventas,2) . "</p>
        <p><b>Efectivo:</b> $" . number_format($total_efectivo,2) . "</p>
        <p><b>Tarjeta:</b> $" . number_format($total_tarjeta,2) . "</p>
        <hr>
        <p style='font-size:14px'>Desde: $ventas_desde</p>
        <p style='font-size:14px'>Hasta: $ventas_hasta</p>
    `,
    confirmButtonColor: '#4ade80',
}).then(() => {
    window.location.href = 'index.php';
});
</script>

</body>
</html>
";
exit;

