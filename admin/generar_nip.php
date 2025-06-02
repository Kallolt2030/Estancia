<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "estancia","3306");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

do {
    $nip = strval(rand(100000, 999999));
    $sql = "SELECT nip FROM usuarios WHERE nip = ? UNION SELECT nip FROM pacientes WHERE nip = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nip, $nip);
    $stmt->execute();
    $stmt->store_result();
} while ($stmt->num_rows > 0);

$stmt->close();
$conn->close();

echo $nip;
