<?php
// Iniciar sesión si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
$alumno = null;

// Si el formulario se ha enviado (el DNI se ha proporcionado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];

    // Validar que el DNI no esté vacío
    if (empty($dni)) {
        $mensaje = "Por favor, ingrese un DNI.";
    } else {
        // Buscar al alumno en la base de datos
        $sql = "SELECT a.dni, a.nombre, a.edad, a.fecha_nacimiento, p.plan 
                FROM alumnos a 
                JOIN planes p ON a.id_plan = p.id 
                WHERE a.dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dni);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        // Si el alumno existe
        if (mysqli_num_rows($resultado) > 0) {
            $alumno = mysqli_fetch_assoc($resultado);
        } else {
            $mensaje = "No se encontró un alumno con el DNI $dni.";
        }
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

include_once("index.html");
?>

<div class="container">
    <h2>Buscar Alumno</h2>

    <!-- Mostrar mensaje de error o éxito -->
    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para ingresar el DNI del alumno -->
    <form action="buscarAlumno.php" method="POST">
        <div class="form-group">
            <label for="dni">DNI del Alumno:</label>
            <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese el DNI" maxlength="9" required>
        </div>
        <button type="submit" class="btn btn-primary">Buscar Alumno</button>
    </form>

    <!-- Mostrar información del alumno si se encontró -->
    <?php if ($alumno): ?>
        <h3 class="mt-4">Detalles del Alumno</h3>
        <ul class="list-group">
            <li class="list-group-item"><strong>DNI:</strong> <?php echo $alumno['dni']; ?></li>
            <li class="list-group-item"><strong>Nombre:</strong> <?php echo $alumno['nombre']; ?></li>
            <li class="list-group-item"><strong>Edad:</strong> <?php echo $alumno['edad']; ?></li>
            <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> <?php echo $alumno['fecha_nacimiento']; ?></li>
            <li class="list-group-item"><strong>Plan:</strong> <?php echo $alumno['plan']; ?></li>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
