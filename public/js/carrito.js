let botonesEliminar = document.querySelectorAll(".carrito-eliminar");
const botonVaciar = document.querySelector("#botonVaciar");
const contenedorTotal = document.querySelector("#total");
const tblCarrito = document.querySelector("#tblCarrito");
const botonwhatsapp = document.querySelector("#carrito-whatsapp");
const whatsapp_negocio = document.querySelector("#whatsapp-negocio");
let productosWhatsapp, productosTotal;

function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

document.addEventListener("DOMContentLoaded", function () {
  if (localStorage.getItem("productos-en-carrito") != null) {
    productos = JSON.parse(localStorage.getItem("productos-en-carrito"));
  } else {
    productos = [];
  }
  mostrarProductos();

  botonVaciar.addEventListener("click", vaciarCarrito);

  botonwhatsapp.addEventListener("click", function (e) {
    e.preventDefault();
    if (!productos.length) {
      alerta("Agrega al menos un producto", 2);
      return;
    }
    Swal.fire({
      title: "¿Enviar este pedido por WhatsApp?",
      text: "La tienda confirmará disponibilidad, instalación y forma de pago contigo.",
      icon: "question",
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText: "Sí",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        const phone = whatsapp_negocio.value.replace(/\D/g, "");
        const message = `Hola, deseo cotizar este pedido:\n\n${productosWhatsapp}\n\nTotal referencial: S/ ${productosTotal}\n\nPor favor confirmen disponibilidad, instalación y forma de pago.`;
        window.open(`https://api.whatsapp.com/send?phone=${encodeURIComponent(phone)}&text=${encodeURIComponent(message)}`, "_blank", "noopener");
      }
    });
  });
});

function mostrarProductos() {
  fetch(ruta + "principal/listaProductos", {
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(productos),
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      productosWhatsapp = "";
      let html = '';
      if (data.productos.length > 0) {
        data.productos.forEach((producto) => {
          productosWhatsapp += `(${
            producto.cantidad + " x " + producto.nombre + " = " + producto.precio
          }) `;
          productosTotal = `${data.total}`;
          const id = escapeHtml(producto.id);
          const nombre = escapeHtml(producto.nombre);
          const imagen = encodeURIComponent(String(producto.imagen));
          const precio = escapeHtml(producto.precio);
          const cantidad = escapeHtml(producto.cantidad);
          const subTotal = escapeHtml(producto.subTotal);
          html += `<tr>
            <td class="shoping__cart__item">
                <img src="${ruta + "public/img/productos/" + imagen}" alt="${nombre}" width="50">
                <h5>${nombre}</h5>
            </td>
            <td class="shoping__cart__price">
                S/ ${precio}
            </td>
            <td class="shoping__cart__quantity">
                <div class="quantity">
                    <div class="pro-qty">
                        <input type="text" value="${cantidad}" readonly aria-label="Cantidad">
                    </div>
                </div>
            </td>
            <td class="shoping__cart__total">
                S/ ${subTotal}
            </td>
            <td class="shoping__cart__item__close carrito-eliminar" id="${id}" role="button" tabindex="0" aria-label="Eliminar ${nombre}">
                <span class="icon_close"></span>
            </td>
        </tr>`;
        });
        
      } else {
        html = `<tr>
          <td colspan="5" class="empty-cart">Tu carrito está vacío. Agrega productos para solicitar una cotización.</td>
        </tr>`;
      }
      tblCarrito.innerHTML = html;
      contenedorTotal.textContent = "S/ " + data.total;
      botonwhatsapp.classList.toggle("disabled", data.productos.length === 0);
      botonwhatsapp.setAttribute("aria-disabled", data.productos.length === 0 ? "true" : "false");
      actualizarBotonesEliminar();
    });
}

function actualizarBotonesEliminar() {
  botonesEliminar = document.querySelectorAll(".carrito-eliminar");

  botonesEliminar.forEach((boton) => {
    boton.addEventListener("click", eliminarDelCarrito);
    boton.addEventListener("keydown", function (event) {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        eliminarDelCarrito(event);
      }
    });
  });
}

function eliminarDelCarrito(e) {
  const idBoton = e.currentTarget.id;
  const index = productos.findIndex((producto) => producto.id === idBoton);

  if (index < 0) {
    return;
  }
  productos.splice(index, 1);
  mostrarProductos();

  localStorage.setItem("productos-en-carrito", JSON.stringify(productos));
  alerta("Producto eliminado", 1);
}

function vaciarCarrito() {
  Swal.fire({
    title: "¿Estás seguro?",
    icon: "question",
    html: `Se van a borrar ${productos.length} productos.`,
    showCancelButton: true,
    focusConfirm: false,
    confirmButtonText: "Sí",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      productos.length = 0;
      localStorage.setItem("productos-en-carrito", JSON.stringify(productos));
      mostrarProductos();
      alerta("Carrito vaciado", 1);
    }
  });
}

function alerta(mensaje, type) {
  let color = type == 1 ? "#46cd93" : "#f24734";
  Toastify({
    text: mensaje,
    duration: 3000,
    close: true,
    gravity: "top", // `top` or `bottom`
    position: "right", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: {
      background: color,
      borderRadius: "2rem",
      textTransform: "uppercase",
      fontSize: ".75rem",
    },
    offset: {
      x: "1.5rem", // horizontal axis - can be a number or a string indicating unity. eg: '2em'
      y: "1.5rem", // vertical axis - can be a number or a string indicating unity. eg: '2em'
    },
    onClick: function () {}, // Callback after click
  }).showToast();
}
