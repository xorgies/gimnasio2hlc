<?php
require_once("funcionesBD.php");
$conexion = obtenerConexion();

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $enfoques = $_POST['enfoques'];
    
    $salasSeleccionadas = isset($_POST['salas']) ? $_POST['salas'] : [];
    $especialidadesSeleccionadas = isset($_POST['especialidades']) ? (array) $_POST['especialidades'] : [];

    // Validar campos
    if (empty($dni) || empty($nombre) || empty($edad) || empty($fecha_nacimiento) || empty($enfoques) || count($salasSeleccionadas) == 0 || count($especialidadesSeleccionadas) == 0){
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Insertar en la base de datos
        $sql = "INSERT INTO entrenadores (dni, nombre, edad, fecha_nacimiento, enfoques) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssiss", $dni, $nombre, $edad, $fecha_nacimiento, $enfoques);

        if (mysqli_stmt_execute($stmt)) {
            $error = false;
            foreach ($salasSeleccionadas as $salaId) {
                $sql_sala = "INSERT INTO entrenadores_salas (dni_entrenador,id_sala) VALUES (?, ?)";
                $stmt_sala = mysqli_prepare($conexion, $sql_sala);
                mysqli_stmt_bind_param($stmt_sala, "si", $dni, $salaId);
            
                if (!mysqli_stmt_execute($stmt_sala)) {
                    $error=true;
                    $mensaje = "Error al registrar el entrenador (entrenadores_salas): " . mysqli_error($conexion);
                }
            }

            if(!$error){
                foreach ($especialidadesSeleccionadas as $especialidadId) {
                    $sql_especialidades = "INSERT INTO entrenadores_especialidades (dni_entrenador,id_especialidad) VALUES (?, ?)";
                    $stmt_especialidades = mysqli_prepare($conexion, $sql_especialidades);
                    mysqli_stmt_bind_param($stmt_especialidades, "si", $dni, $especialidadId);
                
                    if (!mysqli_stmt_execute($stmt_especialidades)) {
                        $error=true;
                        $mensaje = "Error al registrar el entrenador (entrenadores_especialidades): " . mysqli_error($conexion);
                    }
                }

                if(!$error){
                    $mensaje = "Entrenador registrado correctamente.";
                }
            }
            
        } else {
            $mensaje = "Error al registrar el alumno: " . mysqli_error($conexion);
        }
    }
}

$sql_salas = "SELECT * FROM salas;";
$result_salas = mysqli_query($conexion, $sql_salas);

$sql_especialidades = "SELECT * FROM especialidades;";
$result_especialidades = mysqli_query($conexion, $sql_especialidades);

mysqli_close($conexion);
include_once("index.html");
?>

<div class="container">
    <h2>Alta de Entrenador</h2>

    <!-- Mensaje de error o éxito -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Formulario para alta de alumno -->
    <form action="altaEntrenador.php" method="POST">
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
            <label class="form-label" for="lstSalas">Salas</label>
            <div id="lstSalas">
                <?php while ($sala = mysqli_fetch_assoc($result_salas)): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="salas[]" type="checkbox" id="inlineCheckboxSala<?= $sala['id']; ?>" value="<?= $sala['id']; ?>">
                        <label class="form-check-label" id="txtSala<?= $sala['id']; ?>" for="inlineCheckboxSala${sala.id}"><?= $sala['sala']; ?></label>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="lstEspecialidades">Especialidades</label>
            <div id="lstEspecialidades">
                <?php while ($especialidad = mysqli_fetch_assoc($result_especialidades)): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="especialidades[]" type="checkbox" id="inlineCheckboxEspecialidad<?= $especialidad['id']; ?>" value="<?= $especialidad['id']; ?>">
                        <label class="form-check-label" id="txtEspecialidad<?= $especialidad['id']; ?>" for="inlineCheckboxEspecialidad${sala.id}"><?= $especialidad['especialidad']; ?></label>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="lstEnfoques">Enfoques</label>
            <textarea class="form-control" id="lstEnfoques" name="enfoques" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Entrenador</button>
    </form>
</div>

</body>
</html>
