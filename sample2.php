<?php

use Datos\DependenciaDAO;

require './datos/DependenciaDAO.php';
// Conexión a la base de datos
$dsn = "pgsql:host=localhost;dbname=tsj";
$username = "postgres";
$password = "root";

try {
    $connection = new \PDO($dsn, $username, $password);
    $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    // Crear una instancia del DAO
    $dependenciaDAO = new DependenciaDAO($connection);

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