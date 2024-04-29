<?php
namespace Datos;
//use Datos\DependenciaDTO;

require './datos/TramitesJPDTO.php';

class TramitesJPDAO {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getTramiteById($id) {
        $query = "SELECT id,sector,tramite,costo,requisitos FROM tsj.tramites_jp WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new TramitesJPDTO($row['id'], $row['sector'], $row['tramite'], $row['costo'], $row['requisitos']);
        }

        return null;
    }

    public function getTramiteBySector($sector) {
        $query = "SELECT id,sector,tramite,costo,requisitos FROM tsj.tramites_jp WHERE sector = :sector";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':sector', $sector);
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = new TramitesJPDTO($row['id'], $row['sector'], $row['tramite'], $row['costo'], $row['requisitos']);
        }
        if ($array) {
            return $array;
        }

        return null;
    }

    /**
     * Devuelve un array de string sectores
     */
    public function getSectores() {
        $query = "SELECT distinct sector FROM tsj.tramites_jp ORDER BY sector ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = $row['sector'];
        }
        if ($array) {
            return $array;
        }

        return null;
    }



    // Otros métodos para insertar, actualizar, eliminar usuarios, etc.
}