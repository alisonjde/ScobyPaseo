<?php
class PaseoDAO {
    private $idPaseo;
    private $idTarifa;
    private $fecha;
    private $hora;
    private $idPaseador;
    
    public function __construct($idPaseo = 0, $idTarifa = 0, $fecha = "", $hora = "", $idPaseador = 0) {
        $this->idPaseo = $idPaseo;
        $this->idTarifa = $idTarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->idPaseador = $idPaseador;
    }
    
    public function consultarTodos() {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora,
                       pa.nombre, pa.nombre as nombre_paseador
                FROM paseo p
                JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
                ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function consultarPorPaseador($idPaseador) {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora
                FROM paseo p
                WHERE p.idPaseador = " . $idPaseador . "
                ORDER BY p.fecha DESC, p.hora DESC";
    }
    
    public function consultar() {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora,
                       pa.idPaseador, pa.nombre as nombre_paseador
                FROM paseo p
                JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
                WHERE p.idPaseo = " . $this->idPaseo;
    }

    public function consultarPendiente() {
        return "SELECT count(idPaseo)
                FROM paseo 
                WHERE estado_paseo_idEstadoPaseo= 1 
                AND paseador_idPaseador = " . $this -> idPaseador;
    }
    
}
?>