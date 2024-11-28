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
        // Verificar si el entrenador existe en la base de datos
        $sql = "SELECT * FROM entrenadores WHERE dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dni);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        // Si el entrenador existe
        if (mysqli_num_rows($resultado) > 0) {
            // Eliminar al entrenador
            $sql_borrar = "DELETE FROM entrenadores WHERE dni = ?";
            $stmt_borrar = mysqli_prepare($conexion, $sql_borrar);
            mysqli_stmt_bind_param($stmt_borrar, "s", $dni);
            if (mysqli_stmt_execute($stmt_borrar)) {
                $mensaje = "El entrenador con DNI $dni ha sido borrado correctamente.";
            } else {
                $mensaje = "Hubo un error al borrar el entrenador.";
            }
        } else {
            $mensaje = "No se encontró un entrenador con el DNI $dni.";
        }
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

include_once("index.html");
?>

<div class="container">
    <h2>Borrar Entrenador</h2>

    <!-- Mostrar mensaje de error o éxito -->
    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para ingresar el DNI del entrenador a borrar -->
    <form action="borrarEntrenador.php" method="POST">
        <div class="form-group">
            <label for="dni">DNI del Entrenador:</label>
            <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese el DNI" maxlength="9" required>
        </div>
        <button type="submit" class="btn btn-danger">Borrar Entrenador</button>
    </form>
</div>

</body>
</html>
