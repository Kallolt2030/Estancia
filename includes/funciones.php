<?php

require 'app.php';

function includeTemplate( string $name, bool $hero = false, bool $hero_image = false, string $hero_image_name = '' ) {
  include TEMPLATES_URL . "/$name.php";
};

// FUNCIÓN PARA VERIFICAR SI EL USUARIO HA INICIADO SESIÓN
function estaAutenticado() : bool {
  session_start(); // Inicia la sesión PHP. Necesario para acceder a las variables de sesión ($_SESSION)

  $auth = $_SESSION['login']; // Toma el valor de la variable de sesión $_SESSION['login'] y lo guarda en $auth. Este valor normalmente se establece en true cuando el usuario inicia sesión correctamente.
  if($auth) {
    return true; // Si $ath tiene un valor verdadero (es decir, el usuario esta autenticado), retorna true
  }
  return false ; // Si $auth no está definido o es falso, retorna false, indicando que el usuario no está autenticado
}

// FUNCIÓN conectarDB
function conectarDB() : mysqli {
  $db = mysqli_connect('localhost', 'root', 'root', 'estancia'); // La función mysqli_connect Intenta conectarse a una base de datos con 4 distintos parámetros
  // Verificar si la conexión falla
  if(!$db) {
    echo "Error, no se pudo conectar";
    exit; // Detener la ejecución del script
  }
  return $db; // Si la conexión es exitosa, devuelve el objeto $db, que es la conexión activa con la base de datos
}