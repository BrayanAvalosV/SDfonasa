<?php
header("Content-Type: application/json");
include(__DIR__ . "/conectar.php");

$rut = $_GET['rut'] ?? null;
if (!$rut) {
  echo json_encode(['exito' => false, 'mensaje' => 'Falta RUT']);
  exit;
}

$regiones = ['Coquimbo', 'Santiago', 'Temuco'];
$bonos = [];

foreach ($regiones as $region) {
  $conn = conectarSQL("BD_Usuarios"); //aqui hay algo raro
  if (!$conn) continue;

  $sql = "SELECT id_bono, rut_paciente, rut_prestador, codigo_prestacion, centro_atencion, fecha_atencion, estado, region FROM bonos WHERE rut_paciente = ?";
  $params = [$rut];

  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) continue;

  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $bonos[] = $row;
  }
}

echo json_encode(['exito' => true, 'bonos' => $bonos]);
