<!-- CREAR UN ADMINISTRADOR MANUALMENTE DESDE PHP -->
<?php

require 'includes/funciones.php';
$db = conectarDB();

$identifier = "AGS82";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$query = " INSERT INTO administrators (identifier, password) VALUES ('{$identifier}', '{$passwordHash}'); ";

mysqli_query($db, $query);