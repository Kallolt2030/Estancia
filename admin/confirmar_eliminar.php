<?php
include '../control/auth.php';
include '../control/bd.php';

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];

    try {
        if ($tipo == 'usuario' && isset($_GET['nip'])) {
            $nip = $_GET['nip'];

            // Primero eliminar pacientes asociados
            $stmt = $pdo->prepare("DELETE FROM pacientes WHERE nip = ?");
            $stmt->execute([$nip]);

            // Luego eliminar el usuario
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE nip = ?");
            $stmt->execute([$nip]);

            $mensaje = "Usuario y paciente asociado eliminados correctamente";
        } elseif ($tipo == 'paciente' && isset($_GET['id'])) {
            $id_paciente = $_GET['id'];

            // Eliminar solo paciente por ID
            $stmt = $pdo->prepare("DELETE FROM pacientes WHERE id_paciente = ?");
            $stmt->execute([$id_paciente]);

            $mensaje = "Paciente eliminado correctamente";
        }

        echo "<script>alert('$mensaje'); window.location.href = 'editar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar: " . addslashes($e->getMessage()) . "'); window.location.href = 'editar.php';</script>";
    }
    exit();
} else {
    header("Location: editar.php");
    exit();
}
?>
