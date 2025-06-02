document.getElementById("form-login").addEventListener("submit", async function (e) {
  e.preventDefault();

  const usuario = document.getElementById("usuario").value.trim();
  const clave = document.getElementById("clave").value.trim();
  const error = document.getElementById("error");

  error.classList.add("oculto");
  error.textContent = "";

  if (!usuario || !clave) {
    error.textContent = "Por favor completa todos los campos.";
    error.classList.remove("oculto");
    return;
  }

  const datos = new FormData();
  datos.append("rut", usuario);
  datos.append("clave", clave);

  try {
    const respuesta = await fetch("php/login.php", {
      method: "POST",
      body: datos,
    });

    const resultado = await respuesta.json();

    if (resultado.exito) {
      localStorage.setItem("usuario", resultado.rut);
      localStorage.setItem("nombre", resultado.nombre);
      window.location.href = "index.html";
    } else {
      error.textContent = resultado.mensaje || "Credenciales incorrectas";
      error.classList.remove("oculto");
    }
  } catch (err) {
    error.textContent = "Error de conexión con el servidor.";
    error.classList.remove("oculto");
  }
});
