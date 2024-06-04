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
        $query = "SELECT id,fecha_inicio, fecha_fin, novedad, descripcion, tipo, enlace FROM tsj.novedads WHERE id = :id";
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
     * Devuelve un array de novedades vigente (fecha_fin < hoy)
     */
    public function getNovedades() {
        $query = "SELECT id,fecha_inicio,fecha_fin,novedad,descripcion,tipo,enlace FROM tsj.novedads ORDER BY fecha_inicio ASC";
        $statement = $this->connection->prepare($query);
        
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = new NovedadDTO($row['id'], $row['fecha_inicio'], $row['fecha_fin'], $row['novedad'], $row['descripcion'], $row['tipo'], $row['enlace']);
        }
        if ($array) {
            return $array;
        }

        return null;
    }

}