<?php
class TamañoDAO {
    private $idTamaño;
    private $tamaño;

    public function __construct($idTamaño = 0, $tamaño = "") {
        $this->idTamaño = $idTamaño;
        $this->tamaño = $tamaño;
    }

    public function consultar() {
        return "SELECT idTamaño, tamaño FROM tamaño ORDER BY tamaño";
    }
}
?>