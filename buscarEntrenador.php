<?php
// Iniciar sesión si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
$entrenador = null;

// Si el formulario se ha enviado (el DNI se ha proporcionado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];

    // Validar que el DNI no esté vacío
    if (empty($dni)) {
        $mensaje = "Por favor, ingrese un DNI.";
    } else {
        // Buscar al entrenador en la base de datos
        $sql = "SELECT e.dni, 
                       e.nombre, 
                       e.edad, 
                       e.fecha_nacimiento, 
                       e.enfoques,
                        (SELECT GROUP_CONCAT(salas.sala SEPARATOR ', ')
                        FROM salas 
                        WHERE id IN (SELECT id_sala 
                                        from entrenadores_salas
                                        where entrenadores_salas.dni_entrenador = e.dni
                                    )
                        ) AS 'salas',
                        (SELECT GROUP_CONCAT(especialidades.especialidad SEPARATOR ', ')
                        FROM especialidades 
                        WHERE id IN (SELECT id_especialidad 
                                        from entrenadores_especialidades
                                        where entrenadores_especialidades.dni_entrenador = e.dni
                                    )
                        ) AS 'especialidades' 
                FROM entrenadores e 
                WHERE e.dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dni);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        // Si el entrenador existe
        if (mysqli_num_rows($resultado) > 0) {
            $entrenador = mysqli_fetch_assoc($resultado);
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
    <h2>Buscar entrenador</h2>

    <!-- Mostrar mensaje de error o éxito -->
    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para ingresar el DNI del entrenador -->
    <form action="buscarEntrenador.php" method="POST">
        <div class="form-group">
            <label for="dni">DNI del entrenador:</label>
            <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese el DNI" maxlength="9" required>
        </div>
        <button type="submit" class="btn btn-primary">Buscar entrenador</button>
    </form>

    <!-- Mostrar información del entrenador si se encontró -->
    <?php if ($entrenador): ?>
        <h3 class="mt-4">Detalles del entrenador</h3>
        <ul class="list-group">
            <li class="list-group-item"><strong>DNI:</strong> <?php echo $entrenador['dni']; ?></li>
            <li class="list-group-item"><strong>Nombre:</strong> <?php echo $entrenador['nombre']; ?></li>
            <li class="list-group-item"><strong>Edad:</strong> <?php echo $entrenador['edad']; ?></li>
            <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> <?php echo $entrenador['fecha_nacimiento']; ?></li>
            <li class="list-group-item"><strong>Enfoque:</strong> <?php echo $entrenador['enfoques']; ?></li>
            <li class="list-group-item"><strong>Salas:</strong> <?php echo $entrenador['salas']; ?></li>
            <li class="list-group-item"><strong>Especialidades:</strong> <?php echo $entrenador['especialidades']; ?></li>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
