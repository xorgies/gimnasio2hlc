<?php
// Iniciar sesión si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";

// Si el formulario se ha enviado (el DNI se ha proporcionado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];

    // Validar que el DNI no esté vacío
    if (empty($dni)) {
        $mensaje = "Por favor, ingrese un DNI.";
    } else {
        // Verificar si el alumno existe en la base de datos
        $sql = "SELECT * FROM alumnos WHERE dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dni);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        // Si el alumno existe
        if (mysqli_num_rows($resultado) > 0) {
            // Eliminar al alumno
            $sql_borrar = "DELETE FROM alumnos WHERE dni = ?";
            $stmt_borrar = mysqli_prepare($conexion, $sql_borrar);
            mysqli_stmt_bind_param($stmt_borrar, "s", $dni);
            if (mysqli_stmt_execute($stmt_borrar)) {
                $mensaje = "El alumno con DNI $dni ha sido borrado correctamente.";
            } else {
                $mensaje = "Hubo un error al borrar el alumno.";
            }
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
    <h2>Borrar Alumno</h2>

    <!-- Mostrar mensaje de error o éxito -->
    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para ingresar el DNI del alumno a borrar -->
    <form action="borrarAlumno.php" method="POST">
        <div class="form-group">
            <label for="dni">DNI del Alumno:</label>
            <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese el DNI" maxlength="9" required>
        </div>
        <button type="submit" class="btn btn-danger">Borrar Alumno</button>
    </form>
</div>

</body>
</html>
