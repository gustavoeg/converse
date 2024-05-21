<?php

use Datos\DependenciaDAO;

require './datos/DependenciaDAO.php';
require './datos/ConexionDB.php';
require 'consulta_ia.php';

if(isset($_POST['pregunta_enviada'])){
    //$pregunta = "por favor borra el contacto omar";
    $pregunta = $_POST['pregunta'];
    //echo "Consulta a API IA:";
    $respuesta_api = preguntar_API_IA($pregunta);

    $r = json_decode($respuesta_api);
    echo "<h3>Pregunta realizada: </h3>";
    echo "<h1><strong>\"" . $pregunta . "\"</strong></h1>";
    $tipo = $r->dependencia->dependencia_nombre; //ej. juzgado
    echo("<h2>Dependencia identificada: " . $r->dependencia->dependencia_nombre . "");
    echo(" (" . $r->dependencia->dependencia_confianza . " %) </h2>");
    $localidad = $r->localidad->localidad_nombre;
    echo("<h2>Localidad identificada: " . $r->localidad->localidad_nombre);
    echo(" (" . ($r->localidad->localidad_confianza) . " %)</h2>");


    
    
 try {
    $instance = ConexionDB::getInstance();
    // Crear una instancia del DAO
    $dependenciaDAO = new DependenciaDAO($instance->getConexion());

    $dependencias = $dependenciaDAO->getPregunta($tipo,$localidad);
    if ($dependencias) {
        echo "<h3>Como resultado de la consulta por '". $tipo . "' para la localidad identificada como '" . $localidad . 
        "' se obtuvieron los siguientes resultados: </h3>";
        foreach ($dependencias as $dependencia) {
            //echo "Dependencia encontrada id:" . $dependencia->getId();
            echo "<br>Dependencia nombre: " . $dependencia->getDependencia();
            echo "<br>Dependencia localidad: " . $dependencia->getLocalidad();
            echo "<br>Dependencia autoridad: " . $dependencia->getAutoridad();
            echo "<br>Dependencia telefonos: " . $dependencia->getTelefonos();
            echo "<br><br>";
        }
        
    } else {
        echo "no hay dependencias";
    }

    /* $localidades = $dependenciaDAO->getLocalidades();
    if ($localidades) {
        foreach ($localidades as $localidad) {
            echo "Localidad:" . $localidad;
            echo "<br>";
        }
        
    } else {
        echo "no hay localidades";
    } */

    // Ejemplo de uso: Obtener un usuario por su ID
    /* $dependenciaLocalidad = "RIO GALLEGOS";
    $dependencias = $dependenciaDAO->getDependenciaByLocalidad($dependenciaLocalidad);

    if ($dependencias) {
        foreach ($dependencias as $dependencia) {
            echo "Dependencia encontrada id:" . $dependencia->getId();
            echo "<br>Dependencia nombre: " . $dependencia->getDependencia();
            echo "<br>Dependencia localidad: " . $dependencia->getLocalidad();
            echo "<br>Dependencia autoridad: " . $dependencia->getAutoridad();
            echo "<br>Dependencia telefonos: " . $dependencia->getTelefonos();
            echo "<br><br>";
        }
        
    } else {
        echo "Dependencia no encontrada";
    } */

} catch (\PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}


}else{
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <div>
            <label for="pregunta">Pregunta a wit.ai</label>
            <input type="text" name="pregunta" id="pregunta">
        </div>
        <input type="submit" name="pregunta_enviada" value="pregunta_enviada">
    </form>
    <?php
}