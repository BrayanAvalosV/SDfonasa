<?php
header("Content-Type: application/json");
include(__DIR__ . "/conectar.php");

$id_bono = $_POST['id_bono'] ?? null;
$region = $_POST['region'] ?? null;

if (!$id_bono || !$region) {
    echo json_encode(['exito' => false, 'mensaje' => 'Faltan parámetros.']);
    exit;
}

$conn = conectarSQL("BD_" . $region);
if (!$conn) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo conectar a la base regional']);
    exit;
}

$sql = "UPDATE bonos SET estado = 'Usado' WHERE id_bono = ?";
$params = [$id_bono];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar bono', 'errors' => $errors]);
    exit;
}

echo json_encode(['exito' => true, 'mensaje' => 'Bono marcado como usado']);
?>
