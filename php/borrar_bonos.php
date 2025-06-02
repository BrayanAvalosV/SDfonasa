<?php
header("Content-Type: application/json");
include(__DIR__ . "/conectar.php");

$region = $_POST['region'] ?? null;

if (!$region) {
    echo json_encode(['exito' => false, 'mensaje' => 'Falta parámetro región.']);
    exit;
}

$conn = conectarSQL("BD_" . $region);
if (!$conn) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo conectar a la base regional']);
    exit;
}

$sql = "DELETE FROM bonos";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode(['exito' => false, 'mensaje' => 'Error al eliminar bonos', 'errors' => $errors]);
    exit;
}

echo json_encode(['exito' => true, 'mensaje' => 'Todos los bonos han sido eliminados']);
?>
