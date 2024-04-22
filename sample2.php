<?php

use Datos\DependenciaDAO;

require './datos/DependenciaDAO.php';
require './datos/ConexionDB.php';

try {
    $instance = ConexionDB::getInstance();
    // Crear una instancia del DAO
    $dependenciaDAO = new DependenciaDAO($instance->getConexion());

    $localidades = $dependenciaDAO->getLocalidades();
    if ($localidades) {
        foreach ($localidades as $localidad) {
            echo "Localidad:" . $localidad;
            echo "<br>";
        }
        
    } else {
        echo "no hay localidades";
    }

    // Ejemplo de uso: Obtener un usuario por su ID
    $dependenciaLocalidad = "RIO GALLEGOS";
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
    }

} catch (\PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}