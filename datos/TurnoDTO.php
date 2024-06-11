<?php
namespace Datos;

class TurnoDTO {
    private $id;
    private $id_juzgado;
    private $juzgado_nombre;
    private $inicio;
    private $fin;
    

    public function __construct($id_, $id_juzgado_,$juzgado_nombre_, $inicio_, $fin_) {
        $this->id = $id_;
        $this->id_juzgado = $id_juzgado_;
        $this->juzgado_nombre = $juzgado_nombre_;
        $this->inicio = $inicio_;
        $this->fin = $fin_;
    }




    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Get the value of id
     */ 
    public function getId_juzgado()
    {
        return $this->id_juzgado;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId_juzgado($id)
    {
        $this->id_juzgado = $id;

        return $this;
    }

    /**
     * Get the value of juzgado nombre (dependencia nombre)
     */ 
    public function getJuzgado_nombre()
    {
        return $this->juzgado_nombre;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setJuzgado_nombre($juzgado)
    {
        $this->juzgado_nombre = $juzgado;

        return $this;
    }

    /**
     * Get the value of fecha_inicio
     */ 
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set the value of fecha_inicio
     *
     * @return  self
     */ 
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get the value of fecha_fin
     */ 
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set the value of fecha_fin
     *
     * @return  self
     */ 
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }
}
