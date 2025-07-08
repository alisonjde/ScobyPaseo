<?php
Class EstadoPaseador{
    
    private $idEstadoPaseador;
    private $estado;
    
    public function __construct($idEstadoPaseador = "", $estado = "") {
        $this -> idEstadoPaseador = $idEstadoPaseador;
        $this -> estado = $estado;
    }
    
    public function getIdEstadoPaseador() {
        return $this->idEstadoPaseador;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
}
?>