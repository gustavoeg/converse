<?php
namespace Datos;

require './datos/TurnoDTO.php';

class TurnoDAO {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getTurnoById($id) {
        $query = "SELECT id,id_juzgado,inicio, fin FROM " . $_ENV['DB_SCHEMA'] . ".turnos
        join " . $_ENV['DB_SCHEMA'] . ".dependencias
	    on " . $_ENV['DB_SCHEMA'] . ".dependencias.id = " . $_ENV['DB_SCHEMA'] . ".turnos.id_juzgado WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new TurnoDTO($row['id'], $row['id_juzgado'],$row['juzgado_nombre'], $row['inicio'], $row['fin']);
        }

        return null;
    }

    public function getTurnoByIdJuzgado($id) {
        $query = "SELECT  id,id_juzgado,dependencias.dependencia as juzgado_nombre,inicio, fin FROM " . $_ENV['DB_SCHEMA'] . ".turnos 
    	join " . $_ENV['DB_SCHEMA'] . ".dependencias
	    on " . $_ENV['DB_SCHEMA'] . ".dependencias.id = " . $_ENV['DB_SCHEMA'] . ".turnos.id_juzgado WHERE id_juzgado = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new TurnoDTO($row['id'], $row['id_juzgado'],$row['juzgado_nombre'], $row['inicio'], $row['fin']);
        }

        return null;
    }

    /**
     * Devuelve un array de novedades vigente (fecha_inicio < hoy() < fecha_fin)
     */
    public function getTurnos() {
        $query = "SELECT turnos.id, id_juzgado,dependencias.dependencia as juzgado_nombre, inicio, fin, turnos.created_at, turnos.updated_at
	FROM " . $_ENV['DB_SCHEMA'] . ".turnos
	join " . $_ENV['DB_SCHEMA'] . ".dependencias
	on " . $_ENV['DB_SCHEMA'] . ".dependencias.id = " . $_ENV['DB_SCHEMA'] . ".turnos.id_juzgado
	WHERE inicio <= now()
        and fin >= now()
        ORDER BY inicio ASC";
        $statement = $this->connection->prepare($query);
        
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = new TurnoDTO($row['id'], $row['id_juzgado'],$row['juzgado_nombre'], $row['inicio'], $row['fin']);
        }
        if (isset($array)) {
            return $array;
        }

        return null;
    }
}