<?php
// bd.php: Archivo que establece la conexión a la base de datos utilizando PDO

$host = 'localhost';
$db = 'estancia'; // Nombre de tu base de datos
$user = 'root'; // Usuario de la base de datos
$pass = ''; // Contraseña de la base de datos
$charset = 'utf8mb4'; // Conjunto de caracteres

// Definir el DSN (Data Source Name) para PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones para PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Manejo de errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Formato de resultado
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Desactivar la emulación de sentencias preparadas
];

try {
    // Establecer la conexión con la base de datos utilizando PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Si ocurre un error, mostrar el mensaje
    die('Error de conexión: ' . $e->getMessage());
}
?>
