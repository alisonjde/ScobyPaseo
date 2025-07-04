<?php
Class EstadoPaseadorDAO{
    
    private $idEstadoPaseador;
    private $estado;
    
    public function __construct($idEstadoPaseador = "", $estado = "") {
        $this -> idEstadoPaseador = $idEstadoPaseador;
        $this -> estado = $estado;
    }
    
}
?>