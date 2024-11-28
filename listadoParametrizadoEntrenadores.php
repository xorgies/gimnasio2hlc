<?php
// Iniciar sesión para manejar el estado si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$sql_salas = "SELECT * FROM salas;";
$result_salas = mysqli_query($conexion, $sql_salas);

// Inicializar las variables de las fechas
$salaId = -1;

// Si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener las fechas desde el formulario
    $salaId = $_POST['salaId'];

    // Consulta SQL para obtener entrenadores de una sala dada
    $sql = "SELECT e.dni, e.nombre, e.edad, e.fecha_nacimiento, e.enfoques,
                (SELECT GROUP_CONCAT(especialidades.especialidad SEPARATOR ', ')
                        FROM especialidades 
                        WHERE id IN (SELECT id_especialidad 
                                        from entrenadores_especialidades
                                        where entrenadores_especialidades.dni_entrenador = e.dni
                                    )
                ) AS 'especialidades'
            FROM entrenadores e, entrenadores_salas es, salas s
            WHERE e.dni = es.dni_entrenador and es.id_sala = s.id and s.id = ?
            ORDER BY e.nombre";
    
    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, $sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conexion));
    }
    
    // Enlazar parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "i", $salaId);
    mysqli_stmt_execute($stmt);

    // Obtener el resultado
    $resultado = mysqli_stmt_get_result($stmt);

    // Si no hay resultados
    if (mysqli_num_rows($resultado) == 0) {
        $mensaje = "No se encontraron entrenadores para la sala dada.";
    }
} else {
    $resultado = null;
}

include_once("index.html");
?>

<div class="container">
    <h2>Listado Parametrizado de Entrenadores</h2>
    
    <!-- Formulario para seleccionar las fechas -->
    <form method="post" action="listadoParametrizadoEntrenadores.php">
        <div class="form-group">
            <label for="sala">Sala:</label>
            <select id="sala" name="salaId" class="form-select" required>
                <option value="" disabled selected>Seleccione una sala</option>
                <?php while ($sala = mysqli_fetch_assoc($result_salas)): ?>
                    <option value="<?php echo $sala['id']; ?>"><?php echo $sala['sala']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    
    <!-- Si hay resultados, mostrar la tabla -->
    <?php if ($resultado !== null) : ?>
        <h3>Resultados:</h3>
        <?php if (isset($mensaje)) { echo "<p class='text-danger'>$mensaje</p>"; } ?>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Enfoques</th>
                    <th>Especialidades</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
                    <tr>
                        <td><?= $fila['dni'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td><?= $fila['edad'] ?></td>
                        <td><?= $fila['fecha_nacimiento'] ?></td>
                        <td><?= $fila['enfoques'] ?></td>
                        <td><?= $fila['especialidades'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>

</body>
</html>
