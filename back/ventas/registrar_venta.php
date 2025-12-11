<?php
session_start();
include "../conexion.php"; // usamos $conexion

header("Content-Type: application/json; charset=utf-8");

// Habilitar excepciones de mysqli para usar try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION["id"])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No hay sesión iniciada"]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "JSON inválido"]);
    exit;
}

// Datos que esperamos del frontend
$total        = $data["total"] ?? 0;
$metodo_pago  = $data["metodo_pago"] ?? "";
$detalle      = $data["detalle"] ?? [];

$usuario_id = $_SESSION["id"];

// Validaciones básicas
if ($total <= 0 || empty($metodo_pago) || empty($detalle)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Datos incompletos de la venta"]);
    exit;
}

try {
    // Iniciar transacción
    $conexion->begin_transaction();

    // ==========================
    // 1) Insertar en VENTAS
    // ==========================
    $sqlVenta = "INSERT INTO ventas (total, metodo_pago, usuario_id)
                 VALUES (?, ?, ?)";
    $stmtVenta = $conexion->prepare($sqlVenta);
    $stmtVenta->bind_param("dsi", $total, $metodo_pago, $usuario_id);
    $stmtVenta->execute();

    $venta_id = $stmtVenta->insert_id;

    // ==========================
    // 2) Insertar DETALLE_VENTA
    // ==========================
    $sqlDetalle = "INSERT INTO detalle_venta 
                    (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                   VALUES (?, ?, ?, ?, ?)";
    $stmtDetalle = $conexion->prepare($sqlDetalle);

    foreach ($detalle as $item) {
        $producto_id    = $item["id"];
        $cantidad       = $item["cantidad"];
        $precio_unitario= $item["precio"];
        $subtotal       = $item["subtotal"];

        $stmtDetalle->bind_param(
            "iiidd",
            $venta_id,
            $producto_id,
            $cantidad,
            $precio_unitario,
            $subtotal
        );
        $stmtDetalle->execute();
    }

    // Confirmar transacción
    $conexion->commit();

    echo json_encode([
        "status"   => "success",
        "message"  => "Venta registrada correctamente",
        "venta_id" => $venta_id
    ]);

} catch (Exception $e) {
    // Revertir cambios si algo falla
    $conexion->rollback();
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Error al registrar la venta",
        "error"   => $e->getMessage()
    ]);
}
