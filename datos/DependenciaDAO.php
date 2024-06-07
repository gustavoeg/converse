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
        $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM " . $_ENV['DB_SCHEMA'] . ".dependencias WHERE id = :id";
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
        $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM " . $_ENV['DB_SCHEMA'] . ".dependencias WHERE localidad = :localidad";
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
        $query = "SELECT distinct localidad FROM " . $_ENV['DB_SCHEMA'] . ".dependencias ORDER BY localidad ASC";
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
        $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM " . $_ENV['DB_SCHEMA'] . ".dependencias 
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
            $localidad = $this->getLocalidadFromAPI(strtolower($localidad));
            $query = "SELECT id,dependencia,autoridad,localidad,telefonos FROM " . $_ENV['DB_SCHEMA'] . ".dependencias 
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
                $loc = "RIO GALLEGOS";
                break;
            case 'las heras':
                $loc = "LAS HERAS";
                break;
            case 'truncado':
                $loc = "PICO TRUNCADO";
                break;
            case 'piedra buena':
                $loc = "PIEDRABUENA";
                break;
            case 'deseado':
                $loc = "PUERTO DESEADO";
                break;
            case 'caleta':
                $loc = "CALETA OLIVIA";
                break;
            case 'calafate':
                $loc = "EL CALAFATE";
                break;
            case 'san julian':
                $loc = "PUERTO SAN JULIAN";
                break;
            case 'turbio':
                $loc = "RIO TURBIO";
                break;
            
            default:
                $loc = strtoupper($loc_api);
                break;
        }
        return $loc;
    }
}