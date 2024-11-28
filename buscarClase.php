<?php
session_start();

require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
$clase = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_clase = $_POST['id_clase'];

    if (empty($id_clase)) {
        $mensaje = "Por favor, ingrese un ID de Clase.";
    } else {
        $id_clase = intval($id_clase);  // pasar el id a entero para que no de problemas 

        $sql = "SELECT id_clase, tipo, horario, id_entrenador, sala 
                FROM clases 
                WHERE id_clase = $id_clase";
        $resultado = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $clase = mysqli_fetch_assoc($resultado);
        } else {
            $mensaje = "No se encontró una clase con el ID $id_clase.";
        }
    }
}

mysqli_close($conexion);

include_once("index.html");
?>

<div class="container">
    <h2>Buscar Clase</h2>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <form action="buscarClase.php" method="POST">
        <div class="form-group">
            <label for="id_clase">ID de la Clase:</label>
            <input type="number" class="form-control" id="id_clase" name="id_clase" placeholder="Ingrese el ID de la Clase" required>
        </div>
        <button type="submit" class="btn btn-primary">Buscar Clase</button>
    </form>

    <?php if ($clase): ?>
        <h3 class="mt-4">Detalles de la Clase</h3>
        <ul class="list-group">
            <li class="list-group-item"><strong>ID Clase:</strong> <?php echo $clase['id_clase']; ?></li>
            <li class="list-group-item"><strong>Tipo:</strong> <?php echo $clase['tipo']; ?></li>
            <li class="list-group-item"><strong>Horario:</strong> <?php echo $clase['horario']; ?></li>
            <li class="list-group-item"><strong>ID Entrenador:</strong> <?php echo $clase['id_entrenador']; ?></li>
            <li class="list-group-item"><strong>Sala:</strong> <?php echo $clase['sala']; ?></li>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
