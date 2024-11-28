<?php
// Iniciar sesión si es necesario
session_start();

// Incluir funciones y la conexión a la base de datos
require_once("funcionesBD.php");
$conexion = obtenerConexion();

// Realizar la consulta para obtener todos los alumnos
$sql = "SELECT dni, nombre, edad, fecha_nacimiento, plan FROM alumnos INNER JOIN planes ON alumnos.id_plan = planes.id ORDER BY nombre";

// Ejecutar la consulta
$resultado = mysqli_query($conexion, $sql);

// Verificar si hubo algún error en la consulta
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

include_once("index.html");
?>

<div class="container">
    <h2>Listado de Todos los Alumnos</h2>

    <!-- Mostrar la tabla de alumnos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Fecha de Nacimiento</th>
                <th>Plan</th>
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
                    echo "<td>" . $fila['plan'] . "</td>";
                    echo "</tr>";
                }
            } else {
                // Si no hay resultados, mostrar un mensaje
                echo "<tr><td colspan='5' class='text-center'>No hay alumnos registrados.</td></tr>";
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
