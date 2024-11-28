<?php
// Iniciar sesión para manejar el estado si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

// Inicializar las variables de las fechas
$fechaInicio = $fechaFin = "";

// Si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener las fechas desde el formulario
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    // Consulta SQL para obtener alumnos nacidos entre las dos fechas
    $sql = "SELECT dni, nombre, edad, fecha_nacimiento 
            FROM alumnos 
            WHERE fecha_nacimiento BETWEEN ? AND ? 
            ORDER BY fecha_nacimiento";
    
    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, $sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conexion));
    }
    
    // Enlazar parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "ss", $fechaInicio, $fechaFin);
    mysqli_stmt_execute($stmt);

    // Obtener el resultado
    $resultado = mysqli_stmt_get_result($stmt);

    // Si no hay resultados
    if (mysqli_num_rows($resultado) == 0) {
        $mensaje = "No se encontraron alumnos nacidos entre esas fechas.";
    }
} else {
    $resultado = null;
}

include_once("index.html");
?>

<div class="container">
    <h2>Listado Parametrizado de Alumnos</h2>
    
    <!-- Formulario para seleccionar las fechas -->
    <form method="post" action="listadoParametrizado.php">
        <div class="form-group">
            <label for="fechaInicio">Fecha de inicio:</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" required value="<?= $fechaInicio ?>">
        </div>
        <div class="form-group">
            <label for="fechaFin">Fecha de fin:</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" required value="<?= $fechaFin ?>">
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
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) : ?>
                    <tr>
                        <td><?= $fila['dni'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td><?= $fila['edad'] ?></td>
                        <td><?= $fila['fecha_nacimiento'] ?></td>
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
