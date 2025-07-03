<?php
class PaseoDAO
{
    private $idPaseo;
    private $idTarifa;
    private $fecha;
    private $hora;
    private $idPaseador;

    public function __construct($idPaseo = 0, $idTarifa = 0, $fecha = "", $hora = "", $idPaseador = 0)
    {
        $this->idPaseo = $idPaseo;
        $this->idTarifa = $idTarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->idPaseador = $idPaseador;
    }
    
    public function consultarTodos($id, $rol) {
    $sentencia = "SELECT 
                    p.idPaseo, p.tarifa, p.fecha, p.hora, 
                    pa.idPaseador, pa.nombre, pa.apellido, 
                    pe.idPerro, pe.nombre, 
                    d.idDueño, d.nombre, d.apellido, 
                    e.idEstadoPaseo, e.estado
                    FROM paseo p
                    JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
                    JOIN estado_paseo e ON p.estado_paseo_idEstadoPaseo = e.idEstadoPaseo
                    JOIN paseo_has_perro pp ON p.idPaseo = pp.paseo_idPaseo
                    JOIN perro pe ON pe.idPerro = pp.perro_idPerro
                    JOIN dueño d ON d.idDueño = pe.dueño_idDueño";

    if ($rol == "paseador") {
        $sentencia .= " WHERE pa.idPaseador = " . $id;
    } else if ($rol == "dueño") {
        $sentencia .= " WHERE d.idDueño = " . $id;
    }

    $sentencia .= " ORDER BY p.fecha DESC, p.hora DESC";

    return $sentencia;
}

    
    public function consultarPorPaseador($idPaseador) {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora
            FROM paseo p
            WHERE p.paseador_idPaseador = " . intval($idPaseador) . "
            ORDER BY p.fecha DESC, p.hora DESC";
    }

    public function consultar()
    {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora,
                       pa.idPaseador, pa.nombre as nombre_paseador
                FROM paseo p
                JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
                WHERE p.idPaseo = " . $this->idPaseo;
    }

    public function consultarPendiente()
    {
        return "SELECT count(idPaseo)
                FROM paseo 
                WHERE estado_paseo_idEstadoPaseo= 1 
                AND paseador_idPaseador = " . $this->idPaseador;
    }
}
