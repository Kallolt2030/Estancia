<?php include '../control/auth.php';?>
<?php include '../control/control_user.php'; ?>
<?php include '../includes/headerAD.php'; ?>
<h2>Gestión de Médicos</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Especialidad</th>
        <th>Acciones</th>
    </tr>
    <tr>
        <td>1</td>
        <td>Dra. López</td>
        <td>Geriatría</td>
        <td><a href="#">Editar</a> | <a href="#">Eliminar</a></td>
    </tr>
</table>
<?php include '../includes/footer.php'; ?>
