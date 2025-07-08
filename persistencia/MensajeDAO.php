<?php
class MensajeDAO {
    private $idMensaje;
    private $paseador;
    private $paseo;
    private $dueño;
    private $tarifaNueva;
    private $estado;

    public function __construct($idMensaje = "", $paseador = "", $paseo = "", $dueño = "", $tarifaNueva = "", $estado = "") {
        $this->idMensaje = $idMensaje;
        $this->paseador = $paseador;
        $this->paseo = $paseo;
        $this->dueño = $dueño;
        $this->tarifaNueva = $tarifaNueva;
        $this->estado = $estado;
    }

    public function insertar(){
        return "INSERT INTO mensaje(paseador_idPaseador, paseo_idPaseo, dueño_idDueño, tarifaNueva) 
        VALUES ($this->paseador, $this->paseo, $this->dueño, $this->tarifaNueva)";
    }

    public function existe(){
        return "SELECT idMensaje FROM mensaje WHERE paseador_idPaseador = $this->paseador AND paseo_idPaseo = $this->paseo";
    }

    public function notificacion(){
        return "SELECT COUNT(idMensaje) FROM mensaje WHERE dueño_idDueño = $this->dueño AND estado is NULL";
    }

    public function consultarMensajes(){
        return "SELECT m.idMensaje, pa.idPaseo, pa.fecha, pa.hora, pa.tarifa, m.tarifaNueva,
                   p.idPaseador, p.nombre, p.apellido,
                   pe.idPerro, pe.nombre, m.estado
            FROM mensaje m 
            JOIN paseo pa ON m.paseo_idPaseo = pa.idPaseo
            JOIN paseador p ON m.paseador_idPaseador = p.idPaseador
            JOIN paseo_has_perro php ON pa.idPaseo = php.paseo_idPaseo
            JOIN perro pe ON php.perro_idPerro = pe.idPerro
            JOIN dueño d ON pe.dueño_idDueño = d.idDueño
            WHERE d.idDueño = $this->dueño
            ORDER BY m.idMensaje DESC";
    }

    public function modificarEstado(){
        return "UPDATE mensaje SET estado = $this->estado WHERE idMensaje = $this->idMensaje";
    }

    public function modificarTarifa(){
        return "UPDATE paseo SET tarifa = $this->tarifaNueva WHERE idPaseo = $this->paseo";
    }
}

?>