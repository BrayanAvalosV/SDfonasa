function inicializarEstadoConexion() {
  const boton = document.getElementById("btn-toggle-conexion");
  const indicador = document.getElementById("status-indicator");
  
  // Leer región activa o por defecto "Coquimbo"
  const region = localStorage.getItem("regionActual") || "Coquimbo";

  // Leer estado por región, si no existe se asume online (true)
  let conectado = localStorage.getItem(`conectado_${region}`);
  if (conectado === null) conectado = "true";
  conectado = conectado === "true";

  actualizarUI(conectado);

  // Al cambiar el botón, actualizar estado para la región activa
  boton.addEventListener("click", () => {
    conectado = !conectado;
    localStorage.setItem(`conectado_${region}`, conectado.toString());
    actualizarUI(conectado);

    if (conectado) sincronizarBonos(region);
  });

  // Escuchar cambio de región para actualizar estado y UI
  const regionSelect = document.getElementById("selector-region");
  regionSelect.addEventListener("change", () => {
    const nuevaRegion = regionSelect.value;
    localStorage.setItem("regionActual", nuevaRegion);

    // Leer estado de la nueva región o poner online por defecto
    let estadoNuevaRegion = localStorage.getItem(`conectado_${nuevaRegion}`);
    if (estadoNuevaRegion === null) estadoNuevaRegion = "true";

    conectado = estadoNuevaRegion === "true";
    actualizarUI(conectado);
  });

  function actualizarUI(online) {
    indicador.textContent = online ? "🟢 Online" : "🔴 Offline";
    indicador.className = online ? "status online" : "status offline";
    boton.textContent = online ? "Desconectar" : "Conectar";
  }
}

async function sincronizarBonos(region) {
  let pendientes = JSON.parse(localStorage.getItem("bonosPendientes")) || [];
  if (pendientes.length === 0) return;

  for (let i = pendientes.length - 1; i >= 0; i--) {
    const bono = pendientes[i];
    if (bono.region !== region) continue; // sincroniza sólo bonos de la región actual

    try {
      const datos = new FormData();
      for (const key in bono) {
        datos.append(key, bono[key]);
      }
      datos.append("region", bono.region);

      const response = await fetch("php/guardar_bono.php", {
        method: "POST",
        body: datos,
      });

      const result = await response.json();

      if (result.exito) {
        pendientes.splice(i, 1);
        localStorage.setItem("bonosPendientes", JSON.stringify(pendientes));
        if (typeof cargarBonos === "function") cargarBonos();
      }
    } catch (error) {
      console.error("Error sincronizando bono:", error);
    }
  }
}

document.addEventListener("DOMContentLoaded", inicializarEstadoConexion);
