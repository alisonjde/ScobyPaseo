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

    public function consultarTodos()
    {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora,
                       pa.nombre, pa.nombre as nombre_paseador
                FROM paseo p
                JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
                ORDER BY p.fecha DESC, p.hora DESC";
    }

    public function consultarPorPaseador($idPaseador)
    {
        return "SELECT 
        p.idPaseo, p.fecha, p.hora, p.tarifa,
        pe.idPerro, pe.nombre AS nombre_perro,
        d.idDueño, d.nombre AS nombre_dueño, d.apellido AS apellido_dueño
    FROM paseo p
    JOIN paseo_has_perro pp ON p.idPaseo = pp.paseo_idPaseo
    JOIN perro pe ON pp.perro_idPerro = pe.idPerro
    JOIN dueño d ON pe.dueño_idDueño = d.idDueño
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

    public function buscarPorPaseador($filtros, $idPaseador)
    {
        $condiciones = [];
        foreach ($filtros as $filtro) {
            $condiciones[] = "(
            p.fecha LIKE '%$filtro%' OR
            p.hora LIKE '%$filtro%' OR
            p.tarifa LIKE '%$filtro%' OR
            pe.nombre LIKE '%$filtro%' OR
            d.nombre LIKE '%$filtro%' OR
            d.apellido LIKE '%$filtro%'
        )";
        }

        $consulta = implode(" AND ", $condiciones);

        return "
        SELECT 
            p.fecha, p.hora, p.tarifa,
            pe.nombre AS nombre_perro,
            d.nombre AS nombre_dueño,
            d.apellido AS apellido_dueño
        FROM paseo p
        JOIN paseo_has_perro pp ON p.idPaseo = pp.paseo_idPaseo
        JOIN perro pe ON pp.perro_idPerro = pe.idPerro
        JOIN dueño d ON pe.dueño_idDueño = d.idDueño
        WHERE p.paseador_idPaseador = " . intval($idPaseador) . "
          AND $consulta
        ORDER BY p.fecha DESC";
    }
}
