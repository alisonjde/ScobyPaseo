<?php
class PaseoDAO
{
    private $idPaseo;
    private $tarifa;
    private $fecha;
    private $hora;
    private $idPaseador;
    private $direccion;

    public function __construct($idPaseo = 0, $tarifa = 0, $fecha = "", $hora = "", $idPaseador = 0, $direccion = "")
    {
        $this->idPaseo = $idPaseo;
        $this->tarifa = $tarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->idPaseador = $idPaseador;
        $this->direccion = $direccion;
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

    public function registrar()
    {
        return "INSERT INTO paseo (tarifa, fecha, hora, paseador_idPaseador, estado_paseo_idEstadoPaseo, direccion)
        VALUES ($this->tarifa, '$this->fecha', '$this->hora', $this->idPaseador, 1, '$this->direccion')";
    }



    public function asociarPerro($idPaseo, $idPerro)
    {
        return "INSERT INTO paseo_has_perro (paseo_idPaseo, perro_idPerro)
            VALUES ($idPaseo, $idPerro)";
    }

    public function buscarPaseoExistente($fecha, $hora, $idPaseador)
    {
        return "
        SELECT idPaseo
        FROM paseo
        WHERE fecha = '$fecha'
          AND hora = '$hora'
          AND paseador_idPaseador = $idPaseador
        LIMIT 1
    ";
    }

    public function contarPerrosEnPaseo($idPaseo)
    {
        return "
        SELECT COUNT(*) as cantidad
        FROM paseo_has_perro
        WHERE paseo_idPaseo = $idPaseo
    ";
    }
}
