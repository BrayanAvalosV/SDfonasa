// Verificar sesión activa
function verificarSesion() {
  const usuario = localStorage.getItem("usuario");
  if (!usuario) {
    window.location.href = "login.html";
  } else {
    document.getElementById("user-nombre").innerHTML = `
      <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" class="user-icon"> ${usuario}`;
  }
}

// Variables globales
let conectado = true;
let transacciones = JSON.parse(localStorage.getItem("transacciones")) || [];
let pendientes = JSON.parse(localStorage.getItem("pendientes")) || [];

// Cambiar estado de conexión
function toggleConexion() {
  conectado = !conectado;
  const status = document.getElementById("status-indicator");
  if (conectado) {
    status.textContent = "🟢 Online";
    status.className = "online";
    sincronizar();
  } else {
    status.textContent = "🔴 Offline";
    status.className = "offline";
  }
}

// Vender bono
function venderBono() {
  const region = document.getElementById("region").value;
  const bono = `Bono vendido en región ${region} - ${new Date().toLocaleTimeString()}`;

  if (conectado) {
    transacciones.unshift(bono);
    guardarDatos();
  } else {
    pendientes.unshift(bono);
    guardarDatos();
  }

  actualizarVista();
  mostrarAlerta(); // ✅ Solo se llama aquí
}

// Sincronizar pendientes
function sincronizar() {
  transacciones = [...pendientes, ...transacciones];
  pendientes = [];
  guardarDatos();
  actualizarVista();
}

// Guardar en localStorage
function guardarDatos() {
  localStorage.setItem("transacciones", JSON.stringify(transacciones));
  localStorage.setItem("pendientes", JSON.stringify(pendientes));
}

// Actualizar la vista
function actualizarVista() {
  const transaccionesLista = document.getElementById("transacciones");
  const pendientesLista = document.getElementById("pendientes");

  transaccionesLista.innerHTML = transacciones.map(b => `<li>${b}</li>`).join("");
  pendientesLista.innerHTML = pendientes.map(b => `<li>${b}</li>`).join("");
}

// Mostrar alerta visual
function mostrarAlerta() {
  const alerta = document.getElementById("alerta");
  alerta.classList.remove("oculto");
  alerta.classList.add("mostrar");

  setTimeout(() => {
    alerta.classList.remove("mostrar");
    alerta.classList.add("oculto");
  }, 2500);
}

// Cerrar sesión
function cerrarSesion() {
  localStorage.removeItem("usuario");
  window.location.href = "login.html";
}

// Ejecutar al cargar
verificarSesion();
actualizarVista();
