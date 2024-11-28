<?php
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_plan = $_POST['id_plan'];

    // Validar campos
    if (empty($dni) || empty($nombre) || empty($edad) || empty($fecha_nacimiento) || empty($id_plan)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Insertar en la base de datos
        $sql = "INSERT INTO alumnos (dni, nombre, edad, fecha_nacimiento, id_plan) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssisi", $dni, $nombre, $edad, $fecha_nacimiento, $id_plan);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "Alumno registrado correctamente.";
        } else {
            $mensaje = "Error al registrar el alumno: " . mysqli_error($conexion);
        }
    }
}

// Obtener los planes para el select
$sql_planes = "SELECT id, plan FROM planes";
$result_planes = mysqli_query($conexion, $sql_planes);

mysqli_close($conexion);
include_once("index.html");
?>

<div class="container">
    <h2>Alta de Alumno</h2>

    <!-- Mensaje de error o éxito -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Formulario para alta de alumno -->
    <form action="altaAlumno.php" method="POST">
        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" maxlength="9" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" maxlength="40" required>
        </div>
        <div class="mb-3">
            <label for="edad" class="form-label">Edad</label>
            <input type="number" class="form-control" id="edad" name="edad" required>
        </div>
        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>
        <div class="mb-3">
            <label for="id_plan" class="form-label">Plan</label>
            <select id="id_plan" name="id_plan" class="form-select" required>
                <option value="" disabled selected>Seleccione un plan</option>
                <?php while ($plan = mysqli_fetch_assoc($result_planes)): ?>
                    <option value="<?php echo $plan['id']; ?>"><?php echo $plan['plan']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Alumno</button>
    </form>
</div>

</body>
</html>
