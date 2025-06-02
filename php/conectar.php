<?php
function conectarSQL($bd) {
    $server = "LAPTOP-G1CBA8VT";//cambiar si es desde otro pc

    $connectionInfo = [
        "Database" => $bd,
        "UID" => "MiUsuarioPruebas",         // ← tu usuario de SQL Server
        "PWD" => "1234",       // ← la contraseña que le asignaste
        "CharacterSet" => "UTF-8",
        "TrustServerCertificate" => true
    ];

    $conn = sqlsrv_connect($server, $connectionInfo);

    if (!$conn) {
        die(json_encode(["error" => sqlsrv_errors()], JSON_PRETTY_PRINT));
    }

    return $conn;
}
?>

