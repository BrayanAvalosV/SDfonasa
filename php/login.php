<?php
header("Content-Type: application/json");
include(__DIR__ . "/conectar.php");

$rut = $_POST["rut"] ?? null;
$clave = $_POST["clave"] ?? null;

if (!$rut || !$clave) {
    echo json_encode(["exito" => false, "mensaje" => "Faltan datos"]);
    exit;
}

$conn = conectarSQL("BD_Coquimbo");
if (!$conn) {
    echo json_encode(["exito" => false, "mensaje" => "Error de conexión a base de datos"]);
    exit;
}

$sql = "SELECT nombre FROM usuarios WHERE rut = ? AND clave = ?";
$params = [$rut, $clave];

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    echo json_encode(["exito" => false, "mensaje" => "Error en consulta"]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$row) {
    echo json_encode(["exito" => false, "mensaje" => "Usuario o clave incorrectos"]);
    exit;
}

echo json_encode(["exito" => true, "nombre" => $row["nombre"], "rut" => $rut]);
