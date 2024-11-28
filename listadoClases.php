<?php
session_start();

require_once("funcionesBD.php");
$conexion = obtenerConexion();

$sql = "SELECT id_clase, tipo, horario, id_entrenador, sala FROM clases ORDER BY tipo, horario";

$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

include_once("index.html");
?>

<div class="container">
    <h2>Listado de Todas las Clases</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Clase</th>
                <th>Tipo</th>
                <th>Horario</th>
                <th>ID Entrenador</th>
                <th>Sala</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($resultado) > 0) { //para ver si hay clases sino poco hay q ver
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_clase'] . "</td>";
                    echo "<td>" . $fila['tipo'] . "</td>";
                    echo "<td>" . $fila['horario'] . "</td>";
                    echo "<td>" . $fila['id_entrenador'] . "</td>";
                    echo "<td>" . $fila['sala'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No hay clases registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
mysqli_close($conexion);
?>

</body>
</html>
