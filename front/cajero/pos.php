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

<body class="bg-[#f5efe6]">

    <!-- ========== HEADER ========== -->
    <div class="bg-[#d7bfae] p-4 shadow-md mb-6 fixed top-0 left-0 right-0 z-50">

        <div class="flex justify-between items-center">

            <!-- Logo + Cajero -->
            <div class="flex items-center gap-4">
                <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />
                <span class="text-xl font-semibold text-[#4b2e2b]">
                    Atiende: <b><?php echo $_SESSION["nombre"]; ?></b>
                </span>
            </div>

            <!-- Hora + Logout -->
            <div class="flex items-center gap-6">

                <div id="hora" class="text-xl font-bold text-[#4b2e2b]"></div>

                <a href="/korina-pos/back/logout.php"
                   class="flex items-center gap-2 bg-red-500 text-white px-5 py-2.5 rounded-lg hover:bg-red-600 transition font-semibold">

                    <!-- SVG logout -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m-5 5h.01"/>
                    </svg>

                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>


    <!-- ========== CONTENEDOR GENERAL ========== -->
    <div class="flex pt-24">

        <!-- ========== CARRITO ========== -->
        <div id="carrito"
             class="w-1/4 bg-white shadow-xl h-[calc(100vh-6rem)] p-6 fixed right-0 top-24 
                    border-l-4 border-[#c9a38c] rounded-tl-xl">

            <h2 class="text-xl font-bold mb-4 text-[#4b2e2b] flex items-center gap-2">

                <!-- SVG carrito -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#4b2e2b]" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 2.293a1 1 
                          0 00.217 1.32l.083.062a1 1 0 001.32-.217L9 15h6m0 0l1.293 
                          1.293a1 1 0 001.32.217l.083-.062a1 1 0 00.217-1.32L17 13m-6 8a1 
                          1 0 110-2 1 1 0 010 2zm8 0a1 1 0 110-2 1 1 0 010 2z" />
                </svg>

                Carrito
            </h2>

            <div id="carrito-lista" class="h-[65%] overflow-y-auto pr-2 space-y-3">
                <!-- Productos dinámicos -->
            </div>

            <div class="mt-6 border-t border-[#c9a38c] pt-4">
                <div class="flex justify-between text-lg mb-3">
                    <span class="font-semibold text-[#4b2e2b]">Total:</span>
                    <span id="total" class="font-bold text-[#4b2e2b] text-2xl">$0.00 MX</span>
                </div>

                <button id="btn-procesar"
                    class="w-full bg-[#4b2e2b] text-white py-3 rounded-lg font-bold 
                           hover:bg-[#6b423f] transition shadow">
                    Realizar orden
                </button>
            </div>
        </div>


        <!-- ========== CONTENIDO IZQUIERDO ========== -->
        <div class="w-3/4 p-6 mr-[25%]">

            <!-- ========== VISTA 1: CATEGORÍAS ========== -->
            <div id="vista-categorias">

                <h1 class="text-3xl font-bold mb-6 text-[#4b2e2b]">
                    Selecciona una categoría
                </h1>

                <div class="grid grid-cols-3 gap-6">

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
                            class="bg-white shadow-md p-6 rounded-xl text-center 
                                   cursor-pointer hover:scale-105 transition border border-[#ead6c9]">

                            <img src="/korina-pos/front/imagenes/<?php echo $cat[1]; ?>"
                                 class="h-24 mx-auto mb-2">

                            <p class="font-semibold text-[#4b2e2b]">
                                <?php echo $cat[0]; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>


            <!-- ========== VISTA 2: PRODUCTOS ========== -->
            <div id="vista-productos" class="hidden">

                <button onclick="volverCategorias()"
                    class="mb-4 bg-[#c9a38c] text-white px-5 py-2 rounded-lg hover:bg-[#b1876c] font-semibold transition">
                    ← Regresar
                </button>

                <h2 id="titulo-categoria"
                    class="text-3xl font-bold mb-6 text-[#4b2e2b]"></h2>

                <div id="productos-grid"
                     class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                </div>
            </div>

        </div>
    </div>


    <!-- ========== MODAL DE PAGO ========== -->
<div id="modal-pago"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center">

    <div class="bg-white p-6 rounded-xl w-[400px] shadow-xl border border-[#c9a38c]">

        <!-- MÉTODO DE PAGO -->
        <div id="vista-metodo-pago">
            <h2 class="text-xl font-bold mb-4 text-[#4b2e2b]">Método de pago</h2>

            <button onclick="pagoEfectivo()"
                class="w-full bg-[#d7bfae] text-[#4b2e2b] py-2 mb-3 rounded-lg hover:bg-[#c5ad9c] font-semibold">
                Efectivo
            </button>

            <button onclick="pagoTarjeta()"
                class="w-full bg-[#4b2e2b] text-white py-2 rounded-lg hover:bg-[#6b423f] font-semibold">
                Tarjeta
            </button>
        </div>


        <!-- PAGO EN EFECTIVO -->
        <div id="vista-efectivo" class="hidden">

            <h2 class="text-xl font-bold mb-3 text-[#4b2e2b]">Pago en efectivo</h2>

            <p class="text-gray-700 mb-2">Monto recibido:</p>

            <!-- GRID DE BILLETES -->
            <div class="grid grid-cols-3 gap-3 mb-4">

                <button class="bg-blue-100 py-2 rounded-lg font-semibold" onclick="agregarBillete(20)">$20</button>
                <button class="bg-pink-100 py-2 rounded-lg font-semibold" onclick="agregarBillete(50)">$50</button>
                <button class="bg-red-100 py-2 rounded-lg font-semibold" onclick="agregarBillete(100)">$100</button>
                <button class="bg-green-100 py-2 rounded-lg font-semibold" onclick="agregarBillete(200)">$200</button>
                <button class="bg-blue-200 py-2 rounded-lg font-semibold" onclick="agregarBillete(500)">$500</button>
                <button class="bg-gray-200 py-2 rounded-lg font-semibold" onclick="agregarBillete(1000)">$1000</button>

            </div>

            <input id="input-manual" type="number" placeholder="Monto manual"
                class="w-full border px-3 py-2 rounded mb-3" oninput="calcularCambio()">

            <div class="mb-2">
                <p>Total a pagar: <b id="total-pago">$0.00</b></p>
                <p>Monto recibido: <b id="monto-recibido">$0.00</b></p>
                <p class="text-lg mt-2">Cambio: <b id="cambio">$0.00</b></p>
            </div>

            <button onclick="confirmarPagoEfectivo()"
                class="w-full bg-green-600 text-white py-2 rounded-lg mt-4 hover:bg-green-700">
                Confirmar pago
            </button>

            <button onclick="volverMetodoPago()"
                class="w-full bg-gray-400 text-white py-2 rounded-lg mt-2 hover:bg-gray-500">
                Regresar
            </button>

        </div>

    </div>
</div>


    <!-- JS -->
    <script src="/korina-pos/front/cajero/pos.js"></script>

</body>
</html>
