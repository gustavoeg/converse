<?php
namespace Datos;
//use Datos\DependenciaDTO;

require './datos/NovedadDTO.php';

class NovedadDAO {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getNovedadById($id) {
        $query = "SELECT id,fecha_inicio, fecha_fin, novedad, descripcion, tipo, enlace FROM " . $_ENV['DB_SCHEMA'] . ".novedads WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new NovedadDTO($row['id'], $row['fecha_inicio'], $row['fecha_fin'], $row['novedad'], $row['descripcion'], $row['tipo'], $row['enlace']);
        }

        return null;
    }

    /**
     * Devuelve un array de novedades vigente (fecha_inicio < hoy() < fecha_fin)
     */
    public function getNovedades() {
        $query = "SELECT id,fecha_inicio,fecha_fin,novedad,descripcion,tipo,enlace FROM " . $_ENV['DB_SCHEMA'] . ".novedads
        WHERE fecha_inicio <= now()
        and fecha_fin >= now()
        ORDER BY fecha_inicio ASC";
        $statement = $this->connection->prepare($query);
        
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = new NovedadDTO($row['id'], $row['fecha_inicio'], $row['fecha_fin'], $row['novedad'], $row['descripcion'], $row['tipo'], $row['enlace']);
        }
        if (isset($array)) {
            return $array;
        }

        return null;
    }

}