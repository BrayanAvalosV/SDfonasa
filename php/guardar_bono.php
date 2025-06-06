<?php
header("Content-Type: application/json");
include(__DIR__ . "/conectar.php");

$rut_paciente = $_POST['rut_paciente'] ?? null;
$rut_prestador = $_POST['rut_prestador'] ?? null;
$codigo_prestacion = $_POST['codigo_prestacion'] ?? null;
$centro_atencion = $_POST['centro_atencion'] ?? null;
$fecha_atencion = $_POST['fecha_atencion'] ?? null;
$region = $_POST['region'] ?? null;

if (!$rut_paciente || !$rut_prestador || !$codigo_prestacion || !$centro_atencion || !$fecha_atencion || !$region) {
    echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
    exit;
}
//cambiar aqui
$conn = conectarSQL("BD_Coquimbo");

if (!$conn) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se pudo conectar a la base regional']);
    exit;
}

$sql = "INSERT INTO bonos (rut_paciente, rut_prestador, codigo_prestacion, centro_atencion, fecha_atencion, estado, region)
        VALUES (?, ?, ?, ?, ?, 'Pendiente', ?)";

$params = [$rut_paciente, $rut_prestador, $codigo_prestacion, $centro_atencion, $fecha_atencion, $region];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo json_encode(['exito' => false, 'mensaje' => 'Error al guardar bono', 'errors' => $errors]);
    exit;
}

echo json_encode(['exito' => true, 'mensaje' => 'Bono guardado exitosamente']);
?>
