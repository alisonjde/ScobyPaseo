<?php
require_once("persistencia/TamañoDAO.php");
require_once("persistencia/Conexion.php");
class Tamaño {
    private $idTamaño;
    private $tamaño;

    public function __construct($idTamaño = "", $tamaño = "") {
        $this->idTamaño = $idTamaño;
        $this->tamaño = $tamaño;
    }

    public function getIdTamaño() {
        return $this->idTamaño;
    }

    public function getTamaño() {
        return $this->tamaño;
    }

    public function consultar(){
        $conexion = new Conexion();
        $conexion->abrir();
        $tamañoDAO = new TamañoDAO();
        $conexion->ejecutar($tamañoDAO->consultar());
        
        $tamaños = array();
        while (($datos = $conexion->registro())!=null) {
            $tamaño = new Tamaño($datos[0], $datos[1]);
            array_push($tamaños, $tamaño);
        }
        
        return $tamaños;
    }
}
?>