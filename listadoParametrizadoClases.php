<?php
session_start();

require_once("funcionesBD.php");
$conexion = obtenerConexion();

$idEntrenador = "";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEntrenador = $_POST['idEntrenador'];

    $sql = "SELECT id_clase, tipo, horario, sala 
            FROM clases 
            WHERE id_entrenador = $idEntrenador
            ORDER BY horario";

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado || mysqli_num_rows($resultado) == 0) {
        $mensaje = "No se encontraron clases para el entrenador indicado.";
    }
} else {
    $resultado = null;
}

include_once("index.html");
?>

<div class="container">
    <h2>Listado Parametrizado de Clases</h2>
    
    <form method="post" action="listadoParametrizadoClases.php">
        <div class="form-group">
            <label for="idEntrenador">ID del Entrenador:</label>
            <input type="number" id="idEntrenador" name="idEntrenador" class="form-control" required value="<?= $idEntrenador ?>">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    
    <?php if ($resultado !== null) : ?>
        <h3>Resultados:</h3>
        <?php if ($mensaje) { echo "<p class='text-danger'>$mensaje</p>"; } ?>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Clase</th>
                    <th>Tipo</th>
                    <th>Horario</th>
                    <th>Sala</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
                    <tr>
                        <td><?= $fila['id_clase'] ?></td>
                        <td><?= $fila['tipo'] ?></td>
                        <td><?= $fila['horario'] ?></td>
                        <td><?= $fila['sala'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
mysqli_close($conexion);
?>

</body>
</html>
