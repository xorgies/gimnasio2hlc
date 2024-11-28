<?php
session_start();

require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
$clase = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['buscar_clase'])) {
        $id_clase = $_POST['id_clase'];

        if (empty($id_clase)) {
            $mensaje = "Por favor, ingrese un ID de Clase.";
        } else {
            $sql = "SELECT * FROM clases WHERE id_clase = $id_clase";
            $resultado = mysqli_query($conexion, $sql);

            if (mysqli_num_rows($resultado) > 0) {
                $clase = mysqli_fetch_assoc($resultado);
            } else {
                $mensaje = "No parece que haya una clase con el id $id_clase.";
            }
        }
    }

    if (isset($_POST['borrar_clase'])) {
        $id_clase = $_POST['id_clase'];

        $sql_borrar = "DELETE FROM clases WHERE id_clase = $id_clase";
        $resultado_borrar = mysqli_query($conexion, $sql_borrar);

        if ($resultado_borrar) {
            $mensaje = "La clase con id $id_clase ha sido borrada correctamente.";
            $clase = null;
        } else {
            $mensaje = "No se ha podido borrar la clase.";
        }
    }
}

mysqli_close($conexion);

include_once("index.html");
?>

<div class="container">
    <h2>Borrar Clase</h2>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php if (!$clase): ?>
        <form action="borrarClase.php" method="POST">
            <div class="form-group">
                <label for="id_clase">ID de la Clase:</label>
                <input type="number" class="form-control" id="id_clase" name="id_clase" placeholder="Ingrese el ID de la Clase" required>
            </div>
            <button type="submit" class="btn btn-primary" name="buscar_clase">Buscar Clase</button>
        </form>
    <?php else: ?>
        <h3 class="mt-4">Detalles de la Clase</h3>
        <ul class="list-group">
            <li class="list-group-item"><strong>ID Clase:</strong> <?php echo $clase['id_clase']; ?></li>
            <li class="list-group-item"><strong>Nombre de la Clase:</strong> <?php echo $clase['tipo']; ?></li>
            <li class="list-group-item"><strong>Horario:</strong> <?php echo $clase['horario']; ?></li>
        </ul>

        <form action="borrarClase.php" method="POST" onsubmit="return confirmarBorrado();">
            <input type="hidden" name="id_clase" value="<?php echo $clase['id_clase']; ?>">
            <button type="submit" class="btn btn-danger" name="borrar_clase">Borrar Clase</button>
        </form>
    <?php endif; ?>
</div>

<script>
    function confirmarBorrado() {
        return confirm("¿Estás seguro de que quieres borrar esta clase? Esta acción tendrá consecuencias.");
    }
</script>

</body>
</html>

