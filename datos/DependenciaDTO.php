<?php
namespace Datos;

class DependenciaDTO {
    private $id;
    private $dependencia;
    private $autoridad;
    private $localidad;
    private $telefonos;

    public function __construct($id_, $dependencia_,$autoridad_,$localidad_,$telefonos_) {
        $this->id = $id_;
        $this->dependencia = $dependencia_;
        $this->autoridad = $autoridad_;
        $this->localidad = $localidad_;
        $this->telefonos = $telefonos_;
    }


    /**
     * Get the value of dependencia
     */ 
    public function getDependencia()
    {
        return $this->dependencia;
    }

    /**
     * Set the value of dependencia
     *
     * @return  self
     */ 
    public function setDependencia($dependencia)
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    /**
     * Get the value of autoridad
     */ 
    public function getAutoridad()
    {
        return $this->autoridad;
    }

    /**
     * Set the value of autoridad
     *
     * @return  self
     */ 
    public function setAutoridad($autoridad)
    {
        $this->autoridad = $autoridad;

        return $this;
    }

    /**
     * Get the value of localidad
     */ 
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set the value of localidad
     *
     * @return  self
     */ 
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get the value of telefonos
     */ 
    public function getTelefonos()
    {
        return $this->telefonos;
    }

    /**
     * Set the value of telefonos
     *
     * @return  self
     */ 
    public function setTelefonos($telefonos)
    {
        $this->telefonos = $telefonos;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}
