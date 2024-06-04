<?php
namespace Datos;

class NovedadDTO {
    private $id;
    private $fecha_inicio;
    private $fecha_fin;
    private $novedad;
    private $descripcion;
    private $tipo;
    private $enlace;

    public function __construct($id_, $fecha_inicio_, $fecha_fin_, $novedad_, $descripcion_, $tipo_, $enlace_) {
        $this->id = $id_;
        $this->fecha_inicio = $fecha_inicio_;
        $this->fecha_fin = $fecha_fin_;
        $this->novedad = $novedad_;
        $this->descripcion = $descripcion_;
        $this->tipo = $tipo_;
        $this->enlace = $enlace_;
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
     * Get the value of fecha_inicio
     */ 
    public function getFecha_inicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set the value of fecha_inicio
     *
     * @return  self
     */ 
    public function setFecha_inicio($fecha_inicio)
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    /**
     * Get the value of fecha_fin
     */ 
    public function getFecha_fin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set the value of fecha_fin
     *
     * @return  self
     */ 
    public function setFecha_fin($fecha_fin)
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    /**
     * Get the value of novedad
     */ 
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set the value of novedad
     *
     * @return  self
     */ 
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of enlace
     */ 
    public function getEnlace()
    {
        return $this->enlace;
    }

    /**
     * Set the value of enlace
     *
     * @return  self
     */ 
    public function setEnlace($enlace)
    {
        $this->enlace = $enlace;

        return $this;
    }
}
