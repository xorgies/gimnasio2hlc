<?php
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $horario = $_POST['horario'];
    $id_entrenador = $_POST['id_entrenador'];
    $sala = $_POST['sala'];

    if (empty($tipo) || empty($horario) || empty($id_entrenador) || empty($sala)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        $sql = "INSERT INTO clases (tipo, horario, id_entrenador, sala) 
                VALUES ('$tipo', '$horario', $id_entrenador, '$sala')";

        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Clase registrada correctamente.";
        } else {
            $mensaje = "Error al registrar la clase: " . mysqli_error($conexion);
        }
    }
}

$sql_entrenadores = "SELECT id, nombre FROM entrenadores";
$result_entrenadores = mysqli_query($conexion, $sql_entrenadores);

mysqli_close($conexion);
include_once("index.html");
?>

<div class="container">
    <h2>Alta de Clase</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form action="altaClases.php" method="POST">
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Clase</label>
            <input type="text" class="form-control" id="tipo" name="tipo" required>
        </div>
        <div class="mb-3">
            <label for="horario" class="form-label">Horario</label>
            <input type="time" class="form-control" id="horario" name="horario" required>
        </div>
        <div class="mb-3">
            <label for="id_entrenador" class="form-label">Entrenador</label>
            <select id="id_entrenador" name="id_entrenador" class="form-select" required>
                <option value="" disabled selected>Seleccione un entrenador</option>
                <?php while ($entrenador = mysqli_fetch_assoc($result_entrenadores)): ?>
                    <option value="<?php echo $entrenador['id']; ?>"><?php echo $entrenador['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="sala" class="form-label">Sala</label>
            <input type="text" class="form-control" id="sala" name="sala" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Clase</button>
    </form>
</div>

</body>
</html>
