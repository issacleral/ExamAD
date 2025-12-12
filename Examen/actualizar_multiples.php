<?php
require_once("dbutils.php");
$miConexion = conectarDB();
$mensaje = ""; 

if (isset($_POST['actualizar'])) { 
    
    $filasAtraccion = actualizarAlturaPorAtraccion($miConexion, $_POST['nombre_atraccion'], $_POST['nueva_altura']);
    $filasTurnos = actualizarHorarioTurnosPorAtraccion($miConexion, $_POST['nombre_atraccion'], $_POST['nuevo_horario']);
    if($filasAtraccion > 0 || $filasTurnos > 0){
        $mensaje = '<div class="alert alert-success">Actualización realizada: Altura y Horarios modificados.</div>';
    } else {
        $mensaje = '<div class="alert alert-warning">No se encontró la atracción o no hubo cambios necesarios.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Múltiples</title>
   
</head>
<body>
    <div class="container mt-4">
        <h1>Actualizar Datos Múltiples</h1>
        
        <?php echo $mensaje; ?>

        <form action="actualizar_multiples.php" method="post">
            
            <div class="alert alert-info">
                Introduce el nombre de la atracción para actualizar su altura y los horarios de todos sus turnos asociados.
            </div>

            <h4 class="mt-3">Criterio de Búsqueda</h4>
            <div class="mb-3">
                <label class="form-label">Nombre de la Atracción (Existente)</label>
                <input type="text" class="form-control" name="nombre_atraccion" required>
            </div>

            <hr>
            <h4 class="mt-3">Nuevos Valores</h4>
            <div class="mb-3">
                <label class="form-label">Nueva Altura Mínima</label>
                <input type="number" class="form-control" name="nueva_altura" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nuevo Horario para TODOS sus turnos</label>
                <input type="text" class="form-control" name="nuevo_horario" required>
            </div>
            
            <button type="submit" name="actualizar" class="btn btn-warning">Actualizar Todo</button>
            <a href="index.php" class="btn btn-secondary">Volver al Menú</a>
        </form>
    </div>
</body>
</html>