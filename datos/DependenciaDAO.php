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

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = new DependenciaDTO($row['id'], $row['dependencia'], $row['autoridad'], $row['localidad'], $row['telefonos']);
        }
        if ($array) {
            return $array;
        }

        return null;
    }

    /**
     * Devuelve un array de string localidades
     */
    public function getLocalidades() {
        $query = "SELECT distinct localidad FROM tsj.dependencias ORDER BY localidad ASC";
        $statement = $this->connection->prepare($query);
        //$statement->bindParam(':localidad', $id);
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = $row['localidad'];
        }
        if ($array) {
            return $array;
        }

        return null;
    }



    // Otros métodos para insertar, actualizar, eliminar usuarios, etc.
    public function getPregunta($tipo,$localidad){
        $tipo = strtoupper($tipo);
        $localidad = strtoupper($localidad);
        $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM tsj.dependencias 
        WHERE localidad like '%$localidad%' and dependencia like '%$tipo%'";
        //var_dump($query);
        $statement = $this->connection->prepare($query);
        //$statement->bindParam(':localidad', $localidad);
        //$statement->bindParam(':tipo', $tipo);
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = new DependenciaDTO($row['id'], $row['dependencia'], $row['autoridad'], $row['localidad'], $row['telefonos']);
        }
        if (isset($array)) {
            return $array;
        }

        return null;
    }
}