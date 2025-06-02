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

$conn = conectarSQL("BD_Usuarios");
if ($conn) {
    foreach ($regiones as $region) {
        $sql = "SELECT id_bono, rut_paciente, rut_prestador, codigo_prestacion, centro_atencion, fecha_atencion, estado, region 
                FROM bonos 
                WHERE rut_paciente = ? AND region = ?";
        $params = [$rut, $region];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) continue;

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $bonos[] = $row;
        }
    }
}

echo json_encode(['exito' => true, 'bonos' => $bonos]);
