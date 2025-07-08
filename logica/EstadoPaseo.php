<?php


Class EstadoPaseo{
    
    private $idEstadoPaseo;
    private $estado;
    
    public function __construct($idEstadoPaseo = "", $estado = "") {
        $this -> idEstadoPaseo = $idEstadoPaseo;
        $this -> estado = $estado;
    }
    
    
    public function getIdEstadoPaseo() {
        return $this->idEstadoPaseo;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
}
?>