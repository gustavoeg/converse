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

    public function getRespuestaAPI($respuesta_api){
        if(! empty($respuesta_api)){
            //$r = json_decode($respuesta_api);
            $r = $respuesta_api;
    
            $tipo = $r->dependencia->dependencia_nombre; //ej. juzgado
            //echo("<h2>Dependencia identificada: " . $r->dependencia->dependencia_nombre . "");
            //echo(" (" . $r->dependencia->dependencia_confianza . " %) </h2>");
            $localidad = $r->localidad->localidad_nombre;
            //echo("<h2>Localidad identificada: " . $r->localidad->localidad_nombre);
            //echo(" (" . ($r->localidad->localidad_confianza) . " %)</h2>");
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
        }else{
            return null;
        }
    }

    public function getLocalidadFromAPI($loc_api){
        switch ($loc_api) {
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            case 'gallegos':
                $loc = "";
                break;
            
            default:
                $loc = $loc_api;
                break;
        }
    }
}