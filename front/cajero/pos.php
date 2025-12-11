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

<body class="bg-gray-100">

    <!-- CONTENEDOR GENERAL -->
    <div class="flex">

        <!-- ========== CARRITO (FIJO DERECHA) ========== -->
        <div id="carrito" class="w-1/4 bg-white shadow-lg h-screen p-4 fixed right-0 top-0">
            <h2 class="text-lg font-semibold mb-4">Carrito</h2>

            <div id="carrito-lista" class="h-[70%] overflow-y-auto">
                <!-- Aquí se agregan productos con JS -->
            </div>

            <div class="mt-4 border-t pt-4">
                <div class="flex justify-between text-lg">
                    <span>Total:</span>
                    <span id="total" class="font-bold">$0.00 MX</span>
                </div>

                <button id="btn-procesar"
                    class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">
                    Realizar orden
                </button>
            </div>
        </div>

        <!-- ========== CONTENIDO IZQUIERDO ========== -->
        <div class="w-3/4 p-6 mr-[25%]">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <img src="/korina-pos/front/imagenes/logo.png" class="h-16" />
                    <div>Atiende: <b><?php echo $_SESSION["nombre"]; ?></b></div>
                </div>

                <div id="hora" class="text-xl font-semibold"></div>
            </div>

            <!-- ========== VISTA 1: CATEGORÍAS ========== -->
            <div id="vista-categorias">
                <h1 class="text-2xl font-bold mb-4">Selecciona una categoría</h1>

                <div class="grid grid-cols-3 gap-6">

                    <!-- Tarjeta categoría -->
                    <div class="bg-white shadow-md p-6 rounded-xl text-center cursor-pointer hover:scale-105 transition"
                         onclick="mostrarProductos('Cafés Calientes')">
                        <img src="/korina-pos/front/imagenes/cafe-caliente.png" class="h-24 mx-auto mb-2">
                        <p class="font-semibold">Cafés Calientes</p>
                    </div>

                    <div class="bg-white shadow-md p-6 rounded-xl text-center cursor-pointer hover:scale-105 transition"
                         onclick="mostrarProductos('Cafés Fríos')">
                        <img src="/korina-pos/front/imagenes/cafe-frio.png" class="h-24 mx-auto mb-2">
                        <p class="font-semibold">Cafés Fríos</p>
                    </div>

                    <div class="bg-white shadow-md p-6 rounded-xl text-center cursor-pointer hover:scale-105 transition"
                         onclick="mostrarProductos('Tés')">
                        <img src="/korina-pos/front/imagenes/te.png" class="h-24 mx-auto mb-2">
                        <p class="font-semibold">Tés</p>
                    </div>

                    <div class="bg-white shadow-md p-6 rounded-xl text-center cursor-pointer hover:scale-105 transition"
                         onclick="mostrarProductos('Comidas')">
                        <img src="/korina-pos/front/imagenes/comida.png" class="h-24 mx-auto mb-2">
                        <p class="font-semibold">Comidas</p>
                    </div>

                    <div class="bg-white shadow-md p-6 rounded-xl text-center cursor-pointer hover:scale-105 transition"
                         onclick="mostrarProductos('Postres')">
                        <img src="/korina-pos/front/imagenes/postre.png" class="h-24 mx-auto mb-2">
                        <p class="font-semibold">Postres</p>
                    </div>

                    <div class="bg-white shadow-md p-6 rounded-xl text-center cursor-pointer hover:scale-105 transition"
                         onclick="mostrarProductos('Frappes')">
                        <img src="/korina-pos/front/imagenes/frappe.png" class="h-24 mx-auto mb-2">
                        <p class="font-semibold">Frappes</p>
                    </div>

                </div>
            </div>

            <!-- ========== VISTA 2: PRODUCTOS ========== -->
            <div id="vista-productos" class="hidden">
            
                <button 
                    onclick="volverCategorias()"
                    class="mb-4 bg-gray-300 px-5 py-2 rounded-lg hover:bg-gray-400 font-semibold">
                    ← Regresar
                </button>
            
                <h2 id="titulo-categoria" class="text-3xl font-bold mb-6"></h2>
            
                <div 
                    id="productos-grid"
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Aquí se cargarán los productos con JS -->
                </div>
            
            </div>


        </div>
    </div>

    <!-- ========== MODAL PAGO ========== -->
<div id="modal-pago" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-xl w-[400px]">

        <!-- VISTA 1: Seleccionar método -->
        <div id="vista-metodo-pago">
            <h2 class="text-xl font-bold mb-4">Método de pago</h2>

            <button onclick="pagoEfectivo()"
                class="w-full bg-yellow-500 text-white py-2 mb-3 rounded-lg">
                Efectivo
            </button>

            <button onclick="pagoTarjeta()"
                class="w-full bg-blue-600 text-white py-2 rounded-lg">
                Tarjeta
            </button>
        </div>

        <!-- VISTA 2: Pago en efectivo -->
        <div id="vista-efectivo" class="hidden">
            <h2 class="text-xl font-bold mb-3">Pago en efectivo</h2>

            <p class="text-gray-700 mb-2">Monto recibido:</p>

            <!-- GRID DE BILLETES -->
            <div class="grid grid-cols-3 gap-3 mb-4">

                <!-- $20 Azul pastel -->
                <button class="billete bg-blue-200 py-2 rounded-lg font-semibold"
                    onclick="agregarBillete(20)">$20</button>

                <!-- $50 Rosa pastel -->
                <button class="billete bg-pink-200 py-2 rounded-lg font-semibold"
                    onclick="agregarBillete(50)">$50</button>

                <!-- $100 Rojo suave pastel -->
                <button class="billete bg-red-200 py-2 rounded-lg font-semibold"
                    onclick="agregarBillete(100)">$100</button>

                <!-- $200 Verde pastel -->
                <button class="billete bg-green-200 py-2 rounded-lg font-semibold"
                    onclick="agregarBillete(200)">$200</button>

                <!-- $500 Azul pastel más fuerte -->
                <button class="billete bg-blue-300 py-2 rounded-lg font-semibold"
                    onclick="agregarBillete(500)">$500</button>

                <!-- $1000 Gris pastel -->
                <button class="billete bg-gray-300 py-2 rounded-lg font-semibold"
                    onclick="agregarBillete(1000)">$1000</button>
            </div>


            <!-- ENTRADA MANUAL -->
            <input id="input-manual" type="number" placeholder="Monto manual"
                class="w-full border px-3 py-2 rounded mb-3" oninput="calcularCambio()">

            <!-- TOTAL Y CAMBIO -->
            <div class="mb-2">
                <p>Total a pagar: <b id="total-pago">$0.00</b></p>
                <p>Monto recibido: <b id="monto-recibido">$0.00</b></p>
                <p class="text-lg mt-2">Cambio: <b id="cambio">$0.00</b></p>
            </div>

            <!-- BOTÓN CONFIRMAR -->
            <button onclick="confirmarPagoEfectivo()"
                class="w-full bg-green-600 text-white py-2 rounded-lg mt-4">
                Confirmar pago
            </button>

            <!-- REGRESAR -->
            <button onclick="volverMetodoPago()"
                class="w-full bg-gray-400 text-white py-2 rounded-lg mt-2">
                Regresar
            </button>
        </div>

    </div>
</div>


    <!-- JS -->
    <script src="/korina-pos/front/cajero/pos.js"></script>


</body>
</html>
