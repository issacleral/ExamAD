<?php
function conectarDB()
{
    try {
        $cadenaConexion = "mysql:host=localhost;dbname=lasetanegra";
        $usu = "root";
        $pw = "";
        $db = new PDO($cadenaConexion, $usu, $pw);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $ex) {
        echo "Error conectando " . $ex->getMessage();
        return null;
    }
}


function getAtraccionPorNombre($db, $nombre)
{
    try {
        $query = "SELECT * FROM atraccion WHERE nombre = :nombre";
        $pstmt = $db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $pstmt->execute(array(':nombre' => $nombre));
    
        return $pstmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        echo "Error en getAtraccionPorNombre: " . $ex->getMessage();
    }
    return false;
}

function insertarAtraccion($db, $nombre, $tipo, $alturaMinima)
{
    try {
        $sqlInsert = "INSERT INTO atraccion (nombre, tipo, altura_minima) VALUES (:nombre, :tipo, :altura)";
        $stmt = $db->prepare($sqlInsert);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":altura", $alturaMinima);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (PDOException $ex) {
        echo "Error en insertarAtraccion: " . $ex->getMessage();
    }
    return false;
}



function getEmpleadoPorDNI($db, $dni)
{
    try {
        $query = "SELECT * FROM empleado WHERE dni = :dni";
        $pstmt = $db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $pstmt->execute(array(':dni' => $dni));
        return $pstmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        echo "Error en getEmpleadoPorDNI: " . $ex->getMessage();
    }
    return false;
}

function insertarEmpleado($db, $nombre, $dni)
{
    try {
        $sqlInsert = "INSERT INTO empleado (nombre, dni) VALUES (:nombre, :dni)";
        $stmt = $db->prepare($sqlInsert);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":dni", $dni);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (PDOException $ex) {
        echo "Error en insertarEmpleado: " . $ex->getMessage();
    }
    return false;
}



function insertarTurno($db, $idAtraccion, $idEmpleado, $horario)
{
    try {
        $sqlInsert = "INSERT INTO turno_asignado (id_turno, id_atraccion, id_empleado, horario) VALUES (:idTurno,:idAtraccion, :idEmpleado, :horario)";
        $stmt = $db->prepare($sqlInsert);
        $stmt->bindParam(":idTurno", $idAtraccion);
        $stmt->bindParam(":idAtraccion", $idAtraccion);
        $stmt->bindParam(":idEmpleado", $idEmpleado);
        $stmt->bindParam(":horario", $horario);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (PDOException $ex) {
        echo "Error en insertarTurno: " . $ex->getMessage();
    }
    return false;
}

function getDatosCompletos($db)
{
    $vectorTotal = array();
    try {
        $query = "SELECT T.horario, A.nombre as nombre_atraccion, A.tipo, A.altura_minima, E.nombre as nombre_empleado, E.dni 
                  FROM turno_asignado T 
                  INNER JOIN atraccion A ON T.id_atraccion = A.id_atraccion 
                  INNER JOIN empleado E ON T.id_empleado = E.id_empleado";
        
        $pstmt = $db->prepare($query);
        $pstmt->execute();
        
        while ($fila = $pstmt->fetch(PDO::FETCH_ASSOC)) {
            $vectorTotal[] = $fila;
        }
    } catch (PDOException $ex) {
        echo "Error en getDatosCompletos: " . $ex->getMessage();
    }
    return $vectorTotal;
}


function actualizarAlturaPorAtraccion($db, $nombreAtraccion, $nuevaAltura)
{
    try {
        $sqlUpdate = "UPDATE atraccion SET altura_minima = :altura WHERE nombre = :nombre";
        $stmt = $db->prepare($sqlUpdate);
        $stmt->bindParam(":altura", $nuevaAltura);
        $stmt->bindParam(":nombre", $nombreAtraccion);
        $stmt->execute();
        return $stmt->rowCount(); 
    } catch (PDOException $ex) {
        echo "Error en actualizarAlturaAtraccion: " . $ex->getMessage();
    }
    return 0;
}

function actualizarHorarioTurnosPorAtraccion($db, $nombreAtraccion, $nuevoHorario)
{
    try {
     
        $sqlUpdate = "UPDATE turno_asignado 
                      SET horario = :horario 
                      WHERE id_atraccion = (SELECT id_atraccion FROM atraccion WHERE nombre = :nombre)";
        
        $stmt = $db->prepare($sqlUpdate);
        $stmt->bindParam(":horario", $nuevoHorario);
        $stmt->bindParam(":nombre", $nombreAtraccion);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $ex) {
        echo "Error en actualizarHorarioTurnosPorAtraccion: " . $ex->getMessage();
    }
    return 0;
}
?>