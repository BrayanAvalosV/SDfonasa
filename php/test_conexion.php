<?php
include 'conectar.php';  // Ajusta la ruta si tu archivo de conexión tiene otro nombre o está en otra carpeta

// Cambia "NombreDeTuBaseDeDatos" por el nombre real de tu base de datos
$nombreBD = "BD_Usuarios";

$conn = conectarSQL($nombreBD);

if ($conn) {
    echo "<h2>✅ ¡Conexión exitosa a la base de datos '$nombreBD'!</h2>";
    sqlsrv_close($conn);
} else {
    echo "<h2>❌ Error de conexión.</h2>";
}
?>
