<?php
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
$alumno = null;

// Paso 1: Si se envía el formulario para buscar el alumno
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
    $dni = $_POST['dni'];

    // Buscar el alumno por su DNI
    $sql = "SELECT * FROM alumnos WHERE dni = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $dni);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        $alumno = mysqli_fetch_assoc($resultado); // Cargar los datos del alumno
    } else {
        $mensaje = "No se encontró ningún alumno con el DNI ingresado.";
    }
}

// Paso 2: Si se envía el formulario para modificar el alumno
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])) {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_plan = $_POST['id_plan'];

    // Actualizar los datos del alumno
    $sql = "UPDATE alumnos SET nombre = ?, edad = ?, fecha_nacimiento = ?, id_plan = ? WHERE dni = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sisis", $nombre, $edad, $fecha_nacimiento, $id_plan, $dni);

    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Datos del alumno actualizados correctamente.";
    } else {
        $mensaje = "Error al actualizar los datos: " . mysqli_error($conexion);
    }
}

// Obtener los planes para el select
$sql_planes = "SELECT id, plan FROM planes";
$result_planes = mysqli_query($conexion, $sql_planes);

mysqli_close($conexion);
include_once("index.html");
?>

<div class="container">
    <h2>Modificar Alumno</h2>

    <!-- Mensaje de error o éxito -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Formulario para buscar el alumno -->
    <form action="modificarAlumno.php" method="POST">
        <div class="mb-3">
            <label for="dni" class="form-label">DNI del Alumno</label>
            <input type="text" class="form-control" id="dni" name="dni" maxlength="9" required 
                   value="<?php echo isset($alumno['dni']) ? $alumno['dni'] : ''; ?>">
        </div>
        <button type="submit" name="buscar" class="btn btn-primary">Buscar Alumno</button>
    </form>

    <!-- Formulario para modificar el alumno (solo visible si se encuentra al alumno) -->
    <?php if ($alumno): ?>
        <form action="modificarAlumno.php" method="POST">
            <input type="hidden" name="dni" value="<?php echo $alumno['dni']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" maxlength="40" required 
                       value="<?php echo $alumno['nombre']; ?>">
            </div>
            <div class="mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" class="form-control" id="edad" name="edad" required 
                       value="<?php echo $alumno['edad']; ?>">
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required 
                       value="<?php echo $alumno['fecha_nacimiento']; ?>">
            </div>
            <div class="mb-3">
                <label for="id_plan" class="form-label">Plan</label>
                <select id="id_plan" name="id_plan" class="form-select" required>
                    <?php while ($plan = mysqli_fetch_assoc($result_planes)): ?>
                        <option value="<?php echo $plan['id']; ?>" 
                                <?php echo $alumno['id_plan'] == $plan['id'] ? 'selected' : ''; ?>>
                            <?php echo $plan['plan']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="modificar" class="btn btn-success">Modificar Alumno</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>