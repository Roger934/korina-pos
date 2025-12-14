/* ------------------------------
   VARIABLES GLOBALES
--------------------------------*/
let productos = [];
let carrito = [];
let categoriaActual = "";
let montoRecibido = 0;

function alertSuccess(msg) {
  Swal.fire({
    icon: "success",
    title: msg,
    confirmButtonColor: "#4ade80",
    background: "#fefce8",
  });
}

function alertError(msg) {
  Swal.fire({
    icon: "error",
    title: "Error",
    text: msg,
    confirmButtonColor: "#f87171",
    background: "#fef2f2",
  });
}

function alertInfo(msg) {
  Swal.fire({
    icon: "info",
    title: msg,
    confirmButtonColor: "#93c5fd",
    background: "#eff6ff",
  });
}

/* ------------------------------
   CAMBIAR A VISTA PRODUCTOS
--------------------------------*/
function mostrarProductos(categoria) {
  categoriaActual = categoria;
  document.getElementById("titulo-categoria").innerText = categoria;
  document.getElementById("vista-categorias").classList.add("hidden");
  document.getElementById("vista-productos").classList.remove("hidden");
  cargarProductos(categoria);
}

/* ------------------------------
   VOLVER A CATEGORÍAS
--------------------------------*/
function volverCategorias() {
  document.getElementById("vista-productos").classList.add("hidden");
  document.getElementById("vista-categorias").classList.remove("hidden");
}

/* ------------------------------
   CARGAR PRODUCTOS (CORREGIDO)
--------------------------------*/
async function cargarProductos(categoria) {
  const productosGrid = document.getElementById("productos-grid");
  productosGrid.innerHTML = "Cargando...";

  try {
    let res = await fetch(
      `/korina-pos/back/productos/obtener.php?categoria=${encodeURIComponent(
        categoria
      )}`
    );
    let productos = await res.json();

    productosGrid.innerHTML = "";

    productos.forEach((p) => {
      // Escapar caracteres especiales en el nombre
      const nombreSeguro = p.nombre
        .replace(/'/g, "\\'")
        .replace(/"/g, "&quot;");

      const divProducto = document.createElement("div");
      divProducto.className =
        "bg-white shadow-md p-6 rounded-xl text-center hover:scale-105 transition cursor-pointer";

      divProducto.innerHTML = `
        <img src="/korina-pos/front/imagenes/${p.imagen}"
             class="h-24 mx-auto mb-3 object-contain"
             onerror="this.style.display='none'">
        
        <p class="font-bold text-xl mb-2">${p.nombre}</p>
        <p class="text-gray-600 text-lg mb-3">$${p.precio}</p>

        <button 
            class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 btn-agregar"
            data-id="${p.id}"
            data-nombre="${nombreSeguro}"
            data-precio="${p.precio}">
            Agregar
        </button>
      `;

      // Agregar event listener al botón
      const btnAgregar = divProducto.querySelector(".btn-agregar");
      btnAgregar.addEventListener("click", function () {
        agregarCarrito(
          parseInt(this.dataset.id),
          this.dataset.nombre,
          parseFloat(this.dataset.precio)
        );
      });

      productosGrid.appendChild(divProducto);
    });
  } catch (error) {
    productosGrid.innerHTML = `<p class="text-red-600">Error al cargar productos: ${error.message}</p>`;
  }
}

/* ------------------------------
   AGREGAR PRODUCTO AL CARRITO
--------------------------------*/
function agregarCarrito(id, nombre, precio) {
  const existe = carrito.find((p) => p.id === id);

  if (existe) {
    existe.cantidad++;
  } else {
    carrito.push({
      id,
      nombre,
      precio,
      cantidad: 1,
    });
  }

  actualizarCarrito();
}

/* ------------------------------
   ACTUALIZAR CARRITO VISUAL
--------------------------------*/
function actualizarCarrito() {
  const contenedor = document.getElementById("carrito-lista");
  contenedor.innerHTML = "";

  let total = 0;

  carrito.forEach((p) => {
    total += p.precio * p.cantidad;

    contenedor.innerHTML += `
      <div class="bg-white shadow-md p-3 rounded-lg flex items-center justify-between mb-3">
        <div>
          <p class="font-semibold">${p.nombre}</p>
          <p class="text-sm text-gray-500">${p.cantidad} x $${p.precio}</p>
        </div>

        <div class="flex gap-2">
          <button onclick="sumar(${p.id})" class="bg-green-300 px-2 py-1 rounded">+</button>
          <button onclick="restar(${p.id})" class="bg-yellow-300 px-2 py-1 rounded">-</button>
          <button onclick="eliminar(${p.id})" class="bg-red-300 px-2 py-1 rounded">X</button>
        </div>
      </div>
    `;
  });

  document.getElementById("total").innerText = `$${total.toFixed(2)} MX`;
}

/* ------------------------------
   BOTONES + - X
--------------------------------*/
function sumar(id) {
  carrito.find((p) => p.id === id).cantidad++;
  actualizarCarrito();
}

function restar(id) {
  const prod = carrito.find((p) => p.id === id);
  if (prod.cantidad > 1) prod.cantidad--;
  actualizarCarrito();
}

function eliminar(id) {
  carrito = carrito.filter((p) => p.id !== id);
  actualizarCarrito();
}

/* ------------------------------
   HORA EN TIEMPO REAL
--------------------------------*/
setInterval(() => {
  document.getElementById("hora").innerText = new Date().toLocaleTimeString(
    "es-MX",
    { hour12: false }
  );
}, 1000);

/* ------------------------------
   MODAL DE PAGO
--------------------------------*/
document.getElementById("btn-procesar").addEventListener("click", () => {
  if (carrito.length === 0) {
    alertError("El carrito está vacío.");
    return;
  }

  document.getElementById("modal-pago").classList.remove("hidden");
});

function cerrarModalPago() {
  document.getElementById("modal-pago").classList.add("hidden");

  // Resetear vistas internas
  document.getElementById("vista-metodo-pago").classList.remove("hidden");
  document.getElementById("vista-efectivo").classList.add("hidden");

  // Reset efectivo visual
  montoRecibido = 0;
  const inputManual = document.getElementById("input-manual");
  if (inputManual) {
    inputManual.value = "";
    inputManual.blur();
  }
  document.getElementById("monto-recibido").innerText = "$0.00";
  document.getElementById("cambio").innerText = "$0.00";
}

function mostrarTicketTemporal() {
  let total = carrito.reduce((acc, p) => acc + p.precio * p.cantidad, 0);

  let htmlTicket = `
    <div class='text-left'>
      <p class='font-bold text-lg mb-2'>Resumen de Compra</p>
  `;

  carrito.forEach((p) => {
    htmlTicket += `
      <p class='flex justify-between border-b py-1'>
        <span>${p.cantidad}x ${p.nombre}</span>
        <span>$${(p.precio * p.cantidad).toFixed(2)}</span>
      </p>
    `;
  });

  htmlTicket += `
    <hr class='my-2'>
    <p class='font-bold text-xl text-right'>Total: $${total.toFixed(2)} MX</p>
    </div>
  `;

  Swal.fire({
    title: "Ticket de venta",
    html: htmlTicket,
    confirmButtonColor: "#93c5fd",
    background: "#f1f5f9",
    width: 400,
  });
}

/* ------------------------------
   PAGO EN EFECTIVO
--------------------------------*/
function pagoEfectivo() {
  document.getElementById("vista-metodo-pago").classList.add("hidden");
  document.getElementById("vista-efectivo").classList.remove("hidden");

  let total = carrito.reduce((acc, p) => acc + p.precio * p.cantidad, 0);
  document.getElementById("total-pago").innerText = `$${total.toFixed(2)}`;

  // REINICIAR VARIABLES
  montoRecibido = 0;

  // REINICIAR CAMPOS VISUALES
  const input = document.getElementById("input-manual");
  input.value = "";
  input.blur();

  document.getElementById("monto-recibido").innerText = "$0.00";
  document.getElementById("cambio").innerText = "$0.00";
}

function agregarBillete(valor) {
  montoRecibido += valor;
  document.getElementById(
    "monto-recibido"
  ).innerText = `$${montoRecibido.toFixed(2)}`;
  calcularCambio();
}

function calcularCambio() {
  let manual = document.getElementById("input-manual").value.trim();
  let total = carrito.reduce((acc, p) => acc + p.precio * p.cantidad, 0);

  // Si input manual está vacío → NO modificar montoRecibido
  if (manual === "" || isNaN(manual)) {
    let cambio = montoRecibido - total;
    document.getElementById("cambio").innerText =
      cambio >= 0 ? `$${cambio.toFixed(2)}` : "$0.00";
    return;
  }

  // Si escribió un número, usarlo
  montoRecibido = parseFloat(manual);
  document.getElementById(
    "monto-recibido"
  ).innerText = `$${montoRecibido.toFixed(2)}`;

  let cambio = montoRecibido - total;
  document.getElementById("cambio").innerText =
    cambio >= 0 ? `$${cambio.toFixed(2)}` : "$0.00";
}

function confirmarPagoEfectivo() {
  let total = carrito.reduce((acc, p) => acc + p.precio * p.cantidad, 0);

  if (montoRecibido < total) {
    return Swal.fire({
      icon: "error",
      title: "Monto insuficiente",
      text: "El monto recibido no cubre el total.",
      confirmButtonColor: "#f87171",
    });
  }

  cerrarModalPago();

  Swal.fire({
    icon: "success",
    title: "Pago realizado",
    text: "El pago en efectivo se ha completado correctamente.",
    confirmButtonColor: "#4ade80",
    background: "#f0fdf4",
  }).then(() => {
    guardarVenta("efectivo");
  });
}

function volverMetodoPago() {
  document.getElementById("vista-efectivo").classList.add("hidden");
  document.getElementById("vista-metodo-pago").classList.remove("hidden");
}

/* ------------------------------
   PAGO CON TARJETA
--------------------------------*/
function pagoTarjeta() {
  cerrarModalPago();

  Swal.fire({
    icon: "success",
    title: "Pago con tarjeta",
    text: "La transacción se procesó correctamente.",
    confirmButtonColor: "#4ade80",
    background: "#f0fdf4",
  }).then(() => {
    guardarVenta("tarjeta");
  });
}

/* ------------------------------
   GUARDAR VENTA EN BD
--------------------------------*/
async function guardarVenta(metodo) {
  let total = carrito.reduce((acc, p) => acc + p.precio * p.cantidad, 0);

  let detalle = carrito.map((p) => ({
    id: p.id,
    cantidad: p.cantidad,
    precio: p.precio,
    subtotal: p.precio * p.cantidad,
  }));

  let payload = {
    total: total,
    metodo_pago: metodo,
    detalle: detalle,
  };

  try {
    let respuesta = await fetch("/korina-pos/back/ventas/registrar_venta.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(payload),
    });

    let data = await respuesta.json();

    if (data.status === "success") {
      Swal.fire({
        icon: "success",
        title: "Venta registrada",
        text: "Folio de venta: " + data.venta_id,
        confirmButtonColor: "#4ade80",
      });

      mostrarTicketTemporal();
      limpiarCarrito();
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: data.message || "No se pudo registrar la venta",
        confirmButtonColor: "#f87171",
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: error.message,
      confirmButtonColor: "#f87171",
    });
  }
}

/* ------------------------------
   LIMPIAR CARRITO
--------------------------------*/
function limpiarCarrito() {
  carrito = [];
  actualizarCarrito();
  volverCategorias();

  // RESETEAR EFECTIVO
  montoRecibido = 0;

  const input = document.getElementById("input-manual");
  if (input) {
    input.value = "";
    input.blur();
  }

  document.getElementById("monto-recibido").innerText = "$0.00";
  document.getElementById("cambio").innerText = "$0.00";
}
