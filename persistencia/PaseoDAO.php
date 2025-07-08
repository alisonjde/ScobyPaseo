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
    
    
    public function consultarTodos($id = null, $rol = null, $filtros = []) {
        $sentencia = "SELECT 
                        p.idPaseo, FORMAT(p.tarifa, 2), p.fecha, p.hora, 
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

        $condiciones = [];

        if ($rol == "paseador") {
            $condiciones[] = "pa.idPaseador = " . $id;
        } else if ($rol == "dueño") {
            $condiciones[] = "d.idDueño = " . $id;
        }
        

        // Condiciones de filtros (funcionalidad similar a buscar)
        if (!empty($filtros)) {
            $filtrosCondiciones = [];
            foreach ($filtros as $filtro) {
                if ($rol == "dueño") {
                    // Si el rol es dueño, no filtrar por campos de dueño
                    $filtrosCondiciones[] = "(
                        FORMAT(p.tarifa, 2) LIKE '%$filtro%' OR
                        p.fecha LIKE '%$filtro%' OR
                        p.hora LIKE '%$filtro%' OR
                        pa.nombre LIKE '%$filtro%' OR
                        pa.apellido LIKE '%$filtro%' OR
                        pe.nombre LIKE '%$filtro%' OR
                        e.estado LIKE '%$filtro%'
                    )";
                } else if ($rol == "paseador") {
                    // Si el rol es paseador, no filtrar por campos de paseador
                    $filtrosCondiciones[] = "(
                        FORMAT(p.tarifa, 2) LIKE '%$filtro%' OR
                        p.fecha LIKE '%$filtro%' OR
                        p.hora LIKE '%$filtro%' OR
                        pe.nombre LIKE '%$filtro%' OR
                        d.nombre LIKE '%$filtro%' OR
                        d.apellido LIKE '%$filtro%' OR
                        e.estado LIKE '%$filtro%'
                    )";
                } else {
                    // Para otros roles, filtrar por todos los campos
                    $filtrosCondiciones[] = "(
                        FORMAT(p.tarifa, 2) LIKE '%$filtro%' OR
                        p.fecha LIKE '%$filtro%' OR
                        p.hora LIKE '%$filtro%' OR
                        pa.nombre LIKE '%$filtro%' OR
                        pa.apellido LIKE '%$filtro%' OR
                        pe.nombre LIKE '%$filtro%' OR
                        d.nombre LIKE '%$filtro%' OR
                        d.apellido LIKE '%$filtro%' OR
                        e.estado LIKE '%$filtro%'
                    )";
                }
            }
            $condiciones[] = "(" . implode(" AND ", $filtrosCondiciones) . ")";
        }

        if (!empty($condiciones)) {
            $sentencia .= " WHERE " . implode(" AND ", $condiciones);
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

    public function buscar($filtros) {
    $condiciones = [];
    foreach ($filtros as $filtro) {
        $condiciones[] = "(
            p.fecha LIKE '%$filtro%' OR
            p.hora LIKE '%$filtro%' OR
            pa.nombre LIKE '%$filtro%' OR
            pa.apellido LIKE '%$filtro%' OR
            pe.nombre LIKE '%$filtro%' OR
            d.nombre LIKE '%$filtro%' OR
            d.apellido LIKE '%$filtro%' OR
            e.estado LIKE '%$filtro%'
        )";
    }

    $consulta = implode(" AND ", $condiciones);

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
                JOIN dueño d ON d.idDueño = pe.dueño_idDueño
                WHERE $consulta
                ORDER BY p.fecha DESC, p.hora DESC";

    return $sentencia;
}

    public function consultarTarifa() {
        return "SELECT p.idPaseo, p.fecha, p.hora, p.tarifa, d.idDueño, d.nombre, d.apellido, pe.idPerro, pe.nombre, pe.foto  FROM paseo p
        JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
        JOIN paseo_has_perro pp ON p.idPaseo = pp.paseo_idPaseo
        JOIN perro pe ON pp.perro_idPerro = pe.idPerro
        JOIN dueño d ON pe.dueño_idDueño = d.idDueño
        WHERE p.paseador_idPaseador = $this->idPaseador AND p.estado_paseo_idEstadoPaseo = 2";
    }

    public function cancelarPaseo(){
        return "UPDATE paseo SET estado_paseo_idEstadoPaseo = 4 WHERE idPaseo = $this->idPaseo";
    }


}
