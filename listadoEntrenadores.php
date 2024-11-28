<?php
// Iniciar sesión si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

// Realizar la consulta para obtener todos los alumnos
$sql = "SELECT entrenadores.dni, 
                entrenadores.nombre, 
                entrenadores.edad, 
                entrenadores.fecha_nacimiento, 
                entrenadores.enfoques, 
                (SELECT GROUP_CONCAT(salas.sala SEPARATOR ', ')
                 FROM salas 
                 WHERE id IN (SELECT id_sala 
                                from entrenadores_salas 
                                where entrenadores_salas.dni_entrenador = entrenadores.dni
                             )
                ) AS 'salas',
                (SELECT GROUP_CONCAT(especialidades.especialidad SEPARATOR ', ')
                 FROM especialidades 
                 WHERE id IN (SELECT id_especialidad 
                                from entrenadores_especialidades 
                                where entrenadores_especialidades.dni_entrenador = entrenadores.dni
                             )
                ) AS 'especialidades'
        FROM entrenadores";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $sql);

// Verificar si hubo algún error en la consulta
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

include_once("index.html");
?>

<div class="container">
    <h2>Listado de Todos los Entrenadores</h2>

    <!-- Mostrar la tabla de entrenadores -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Fecha de Nacimiento</th>
                <th>Enfoques</th>
                <th>Salas</th>
                <th>Especialidades</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Si hay alumnos, mostrar los datos en la tabla
            if (mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $fila['dni'] . "</td>";
                    echo "<td>" . $fila['nombre'] . "</td>";
                    echo "<td>" . $fila['edad'] . "</td>";
                    echo "<td>" . $fila['fecha_nacimiento'] . "</td>";
                    echo "<td>" . $fila['enfoques'] . "</td>";
                    echo "<td>" . $fila['salas'] . "</td>";
                    echo "<td>" . $fila['especialidades'] . "</td>";
                    echo "</tr>";
                }
            } else {
                // Si no hay resultados, mostrar un mensaje
                echo "<tr><td colspan='5' class='text-center'>No hay entrenadores registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>

</body>
</html>
