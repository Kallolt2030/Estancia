<!-- INICIAR SESIÓN COMO ADMINISTRADOR -->
<?php
  require 'includes/funciones.php';
  $db = conectarDB();

  // Crea arreglo para guardar errores que serán mostrados si algo falla
  $errores = [];

  // DETECTAR SI EL FORMULARIO FUE ENVIADO
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $identifier = mysqli_real_escape_string($db, filter_var($_POST['identifier']));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if(!$identifier) {
      $errores[] = "El Identificador es obligatorio o no es válido";
    }
    if(!$password) {
      $errores[] = "La Contraseña es Obligatoria";
    }

    if(empty($errores)) {
      $query = "SELECT * FROM administrators WHERE identifier = '{$identifier}'";

      $resultado = mysqli_query($db, $query);

      if($resultado->num_rows) {
        $administrator = mysqli_fetch_assoc($resultado);

        $auth = password_verify($password, $administrator['password']);

        if($auth) {
          session_start();

          $_SESSION['administrator'] = $administrator['identifier'];
          $_SESSION['login'] = true;

          header('Location: /admin1');
        } else {
          $errores[] = "Contraseña Incorrecta";
        }
      } else {
        $errores[] = "El administrador no existe";
      }
    }
  }
  includeTemplate('header');
?>

<main class="login">
  <?php foreach($errores as $error): ?>
    <div>
      <?php echo $error; ?>
    </div>
  <?php endforeach; ?>
  
  <form method="POST" class="login-form" novalidate>
    <h2 class="margin-title">Iniciar Sesión</h2>

    <label for="identifier">Identificador:</label>
    <input type="text" id="identifier" name="identifier" placeholder="Identificador">
      
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" placeholder="Contraseña">
      
    <input type="submit" value="Iniciar Sesión" class="login-button">
  </form>
</main>

<?php
  includeTemplate('footer');
?>