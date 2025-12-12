<?php
require_once("dbutils.php");
$miConexion = conectarDB();
$mensaje = ""; 

if (isset($_POST['insertar'])) { 
    $atraccionExistente = getAtraccionPorNombre($miConexion, $_POST['nombre_atraccion']);
    if (!$atraccionExistente) {
        insertarAtraccion($miConexion, $_POST['nombre_atraccion'], $_POST['tipo'], $_POST['altura']);
        $atraccionExistente = getAtraccionPorNombre($miConexion, $_POST['nombre_atraccion']);
    }
    $id_Atraccion_Recuperado = $atraccionExistente['id_atraccion'];
    $empleadoExistente = getEmpleadoPorDNI($miConexion, $_POST['dni_empleado']);
    
    if (!$empleadoExistente) {
        insertarEmpleado($miConexion, $_POST['nombre_empleado'], $_POST['dni_empleado']);
        $empleadoExistente = getEmpleadoPorDNI($miConexion, $_POST['dni_empleado']);
    }
    $id_Empleado_Recuperado = $empleadoExistente['id_empleado'];
    $resultado = insertarTurno($miConexion, $id_Atraccion_Recuperado, $id_Empleado_Recuperado, $_POST['horario']);
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Completo</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Insertar Atracción, Empleado y Turno</h1>
        
        <?php echo $mensaje; ?>

        <form action="insertar_completo.php" method="post">
            
            <h4 class="mt-3">Datos de la Atracción</h4>
            <div class="mb-3">
               <label class="form-label">Nombre Atracción </label>
                <input type="text" class="form-control" name="nombre_atraccion" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Atracción</label>
                <input type="text" class="form-control" name="tipo" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Altura Mínima (cm)</label>
                <input type="number" class="form-control" name="altura" required>
            </div>

            <hr>
            <h4 class="mt-3">Datos del Empleado</h4>
            <div class="mb-3">
              <label class="form-label">Nombre Empleado </label>
                <input type="text" class="form-control" name="nombre_empleado" required>
            </div>
            <div class="mb-3">
                <label class="form-label">DNI Empleado </label>
                <input type="text" class="form-control" name="dni_empleado" required>
            </div>

            <hr>
            <h4 class="mt-3">Datos del Turno</h4>
            <div class="mb-3">
               <label class="form-label">Horario Turno Asignado </label>
                <input type="text" class="form-control" name="horario" required>
            </div>
            
            <button type="submit" name="insertar" class="btn btn-primary">Insertar</button>
            <a href="index.php" class="btn btn-secondary">Volver al Menú</a>
        </form>
    </div>
</body>
</html>

