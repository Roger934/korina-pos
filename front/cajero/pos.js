<?php
session_start();

// Protección de rol
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "cajero") {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Korina POS</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-amber-50 to-orange-50">

    <!-- ========== HEADER ========== -->
    <div class="bg-gradient-to-r from-amber-800 to-orange-700 p-4 shadow-lg mb-6 fixed top-0 left-0 right-0 z-50">
        <div class="flex justify-between items-center">

            <!-- Logo + Cajero -->
            <div class="flex items-center gap-4">
                <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />
                <span class="text-xl font-semibold text-white">
                    Atiende: <b><?php echo $_SESSION["nombre"]; ?></b>
                </span>
            </div>

            <!-- Hora + Logout -->
            <div class="flex items-center gap-6">

                <div id="hora" class="text-xl font-bold text-amber-100"></div>

                <a href="/korina-pos/back/logout.php"
                   class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 transition shadow-md font-semibold">
                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>


    <!-- ========== CONTENEDOR GENERAL ========== -->
    <div class="flex pt-24">

        <!-- ========== CARRITO ========== -->
        <div id="carrito"
             class="w-1/4 bg-white shadow-2xl h-[calc(100vh-6rem)] p-6 fixed right-0 top-24 border-l-4 border-amber-600 rounded-tl-2xl">

            <h2 class="text-2xl font-bold mb-6 text-amber-900 border-b-2 border-amber-200 pb-3">
                🛒 Carrito
            </h2>

            <div id="carrito-lista" class="h-[65%] overflow-y-auto pr-2 space-y-3">
                <!-- Productos dinámicos -->
            </div>

            <div class="mt-6 border-t-2 border-amber-200 pt-4">
                <div class="flex justify-between text-xl mb-4">
                    <span class="font-semibold text-amber-900">Total:</span>
                    <span id="total" class="font-bold text-amber-900 text-2xl">$0.00 MX</span>
                </div>

                <button id="btn-procesar"
                    class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-amber-700 hover:to-orange-700 transition shadow-lg transform hover:scale-105">
                    Realizar orden 🚀
                </button>
            </div>
        </div>


        <!-- ========== CONTENIDO IZQUIERDO ========== -->
        <div class="w-3/4 p-6 mr-[25%]">

            <!-- ========== VISTA 1: CATEGORÍAS ========== -->
            <div id="vista-categorias">

                <h1 class="text-4xl font-bold mb-8 text-amber-900">
                    ☕ Selecciona una categoría
                </h1>

                <div class="grid grid-cols-3 gap-6">

                    <!-- Tarjetas de categoría -->
                    <?php
                    $categorias = [
                        ["Cafés Calientes", "cafe-caliente.png"],
                        ["Cafés Fríos", "cafe-frio.png"],
                        ["Tés", "te.png"],
                        ["Comidas", "comida.png"],
                        ["Postres", "postre.png"],
                        ["Frappes", "frappe.png"],
                    ];

                    foreach ($categorias as $cat): ?>
                        <div onclick="mostrarProductos('<?php echo $cat[0]; ?>')"
                            class="bg-white shadow-xl p-6 rounded-2xl text-center cursor-pointer hover:scale-110 transition-all border-2 border-amber-200 hover:border-amber-500 hover:shadow-2xl">

                            <img src="/korina-pos/front/imagenes/<?php echo $cat[1]; ?>"
                                 class="h-24 mx-auto mb-3">

                            <p class="font-bold text-amber-900 text-lg">
                                <?php echo $cat[0]; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <!-- ========== VISTA 2: PRODUCTOS ========== -->
            <div id="vista-productos" class="hidden">

                <button onclick="volverCategorias()"
                    class="mb-6 bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-3 rounded-xl hover:from-amber-700 hover:to-orange-700 font-bold transition shadow-lg">
                    ← Regresar a categorías
                </button>

                <h2 id="titulo-categoria"
                    class="text-4xl font-bold mb-8 text-amber-900"></h2>

                <div id="productos-grid"
                     class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- JS carga productos -->
                </div>
            </div>

        </div>
    </div>


    <!-- ========== MODAL PAGO ========== -->
<div id="modal-pago"
     class="hidden fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50">

    <div class="bg-white p-8 rounded-2xl w-[450px] shadow-2xl border-4 border-amber-500">

        <!-- MÉTODO DE PAGO -->
        <div id="vista-metodo-pago">
            <h2 class="text-2xl font-bold mb-6 text-amber-900">💳 Método de pago</h2>

            <button onclick="pagoEfectivo()"
                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-4 mb-4 rounded-xl hover:from-green-600 hover:to-emerald-700 font-bold text-lg shadow-lg transition transform hover:scale-105">
                💵 Efectivo
            </button>

            <button onclick="pagoTarjeta()"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-4 rounded-xl hover:from-blue-600 hover:to-indigo-700 font-bold text-lg shadow-lg transition transform hover:scale-105">
                💳 Tarjeta
            </button>
        </div>


        <!-- PAGO EN EFECTIVO -->
        <div id="vista-efectivo" class="hidden">
            <h2 class="text-2xl font-bold mb-4 text-amber-900">💵 Pago en efectivo</h2>

            <p class="text-gray-700 mb-3 font-semibold">Monto recibido:</p>

            <!-- GRID DE BILLETES -->
            <div class="grid grid-cols-3 gap-3 mb-4">

                <button class="billete bg-gradient-to-br from-blue-300 to-blue-400 py-3 rounded-xl font-bold text-blue-900 shadow-md hover:shadow-xl transition transform hover:scale-105" onclick="agregarBillete(20)">$20</button>
                <button class="billete bg-gradient-to-br from-pink-300 to-pink-400 py-3 rounded-xl font-bold text-pink-900 shadow-md hover:shadow-xl transition transform hover:scale-105" onclick="agregarBillete(50)">$50</button>
                <button class="billete bg-gradient-to-br from-red-300 to-red-400 py-3 rounded-xl font-bold text-red-900 shadow-md hover:shadow-xl transition transform hover:scale-105" onclick="agregarBillete(100)">$100</button>
                <button class="billete bg-gradient-to-br from-green-300 to-green-400 py-3 rounded-xl font-bold text-green-900 shadow-md hover:shadow-xl transition transform hover:scale-105" onclick="agregarBillete(200)">$200</button>
                <button class="billete bg-gradient-to-br from-blue-400 to-blue-500 py-3 rounded-xl font-bold text-blue-900 shadow-md hover:shadow-xl transition transform hover:scale-105" onclick="agregarBillete(500)">$500</button>
                <button class="billete bg-gradient-to-br from-purple-300 to-purple-400 py-3 rounded-xl font-bold text-purple-900 shadow-md hover:shadow-xl transition transform hover:scale-105" onclick="agregarBillete(1000)">$1000</button>

            </div>

            <input id="input-manual" type="number" placeholder="💰 Monto manual"
                class="w-full border-2 border-amber-300 px-4 py-3 rounded-xl mb-4 focus:border-amber-500 focus:ring-2 focus:ring-amber-200" oninput="calcularCambio()">

            <div class="mb-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-200">
                <p class="text-lg">Total a pagar: <b id="total-pago" class="text-amber-900">$0.00</b></p>
                <p class="text-lg">Monto recibido: <b id="monto-recibido" class="text-green-700">$0.00</b></p>
                <p class="text-xl mt-2">Cambio: <b id="cambio" class="text-2xl text-blue-700">$0.00</b></p>
            </div>

            <button onclick="confirmarPagoEfectivo()"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-700 text-white py-3 rounded-xl mt-2 hover:from-green-700 hover:to-emerald-800 font-bold shadow-lg transition transform hover:scale-105">
                ✅ Confirmar pago
            </button>

            <button onclick="volverMetodoPago()"
                class="w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white py-3 rounded-xl mt-3 hover:from-gray-500 hover:to-gray-600 font-bold shadow-lg transition">
                ← Regresar
            </button>
        </div>

    </div>
</div>


    <!-- JS -->
    <script src="/korina-pos/front/cajero/pos.js"></script>

</body>
</html>