<?php
namespace Datos;
//use Datos\DependenciaDTO;

require './datos/DependenciaDTO.php';

class DependenciaDAO {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getDependenciaById($id) {
        $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM tsj.dependencias WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new DependenciaDTO($row['id'], $row['dependencia'], $row['autoridad'], $row['localidad'], $row['telefonos']);
        }

        return null;
    }

    public function getDependenciaByLocalidad($id) {
        $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM tsj.dependencias WHERE localidad = :localidad";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':localidad', $id);
        $statement->execute();

        //$row = $statement->fetch(\PDO::FETCH_ASSOC);

        

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            # code...
            $array[] = new DependenciaDTO($row['id'], $row['dependencia'], $row['autoridad'], $row['localidad'], $row['telefonos']);
        }
        if ($array) {
            return $array;
        }

        return null;
    }

    // Otros métodos para insertar, actualizar, eliminar usuarios, etc.
}