<?php
require_once("funcionesBD.php");

$conexion = obtenerConexion();

// Recibir los datos del formulario
$dni = $_POST['txtDni'];
$nombre = $_POST['txtNombre'];
$edad = $_POST['txtEdad'];
$fecha_nacimiento = $_POST['txtFechaNacimiento'];
$id_plan = $_POST['lstPlan'];

// Consulta SQL para insertar un nuevo alumno
$sql = "INSERT INTO alumnos (dni, id_plan, nombre, edad, fecha_nacimiento) 
        VALUES ('$dni', '$id_plan', '$nombre', '$edad', '$fecha_nacimiento')";

// Ejecutar la consulta
if (mysqli_query($conexion, $sql)) {
    echo "Alumno registrado con éxito.";
} else {
    echo "Error al registrar el alumno: " . mysqli_error($conexion);
}

// Cerrar la conexión
mysqli_close($conexion);
?>
