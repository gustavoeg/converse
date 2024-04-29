<?php
namespace Datos;

class TramitesJPDTO {
    private $id;
    private $sector;
    private $tramite;
    private $costo;
    private $requisitos;

    public function __construct($id_, $sector_,$tramite_,$costo_,$requisitos_) {
        $this->id = $id_;
        $this->sector = $sector_;
        $this->tramite = $tramite_;
        $this->costo = $costo_;
        $this->requisitos = $requisitos_;
    }


    /**
     * Get the value of dependencia
     */ 
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Set the value of dependencia
     *
     * @return  self
     */ 
    public function setTramite($tramite)
    {
        $this->tramite = $tramite;

        return $this;
    }

    /**
     * Get the value of autoridad
     */ 
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set the value of autoridad
     *
     * @return  self
     */ 
    public function setSector($sector)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get the value of localidad
     */ 
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set the value of localidad
     *
     * @return  self
     */ 
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get the value of telefonos
     */ 
    public function getRequisitos()
    {
        return $this->requisitos;
    }

    /**
     * Set the value of telefonos
     *
     * @return  self
     */ 
    public function setRequisitos($requisitos)
    {
        $this->requisitos = $requisitos;

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
