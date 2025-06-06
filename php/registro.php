<?php
header("Content-Type: application/json");
include(__DIR__ . "/conectar.php");

$rut = $_POST['rut'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$clave = $_POST['clave'] ?? null;

if (!$rut || !$nombre || !$clave) {
    echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
    exit;
}

$conn = conectarSQL("BD_Coquimbo");
if (!$conn) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo conectar a la base de datos de usuarios']);
    exit;
}

// Verificar si ya existe el usuario
$sql_check = "SELECT rut FROM usuarios WHERE rut = ?";
$params_check = [$rut];
$stmt_check = sqlsrv_query($conn, $sql_check, $params_check);
if ($stmt_check === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la consulta']);
    exit;
}
if (sqlsrv_has_rows($stmt_check)) {
    echo json_encode(['exito' => false, 'mensaje' => 'El usuario ya existe']);
    exit;
}

$sql = "INSERT INTO usuarios (rut, nombre, clave) VALUES (?, ?, ?)";
$params = [$rut, $nombre, $clave];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar usuario', 'errors' => $errors]);
    exit;
}

echo json_encode(['exito' => true]);
