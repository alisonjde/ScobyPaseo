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
    
    
    public function consultarTodos2($id = "", $rol = "") {
        $sql = "SELECT 
                p.idPaseo, p.tarifa, p.fecha, p.hora, 
                pa.idPaseador, pa.nombre, pa.apellido, 
                pe.idPerro, pe.nombre, pe.foto,
                d.idDueño, d.nombre, d.apellido, 
                e.idEstadoPaseo, e.estado
            FROM paseo p
            JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
            JOIN estado_paseo e ON p.estado_paseo_idEstadoPaseo = e.idEstadoPaseo
            JOIN paseo_has_perro pp ON p.idPaseo = pp.paseo_idPaseo
            JOIN perro pe ON pe.idPerro = pp.perro_idPerro
            JOIN dueño d ON d.idDueño = pe.dueño_idDueño";

        if ($rol === "paseador") {
            $sql .= " WHERE pa.idPaseador = " . intval($id);
        } else if ($rol === "dueño") {
            $sql .= " WHERE d.idDueño = " . intval($id);
        }

        $sql .= " ORDER BY p.fecha DESC, p.hora DESC";

        return $sql;
    }


    public function consultar()
    {
        return "SELECT p.idPaseo, p.tarifa, p.fecha, p.hora,
               pa.idPaseador, pa.nombre, pa.apellido,
               p.direccion
        FROM paseo p
        JOIN paseador pa ON p.paseador_idPaseador = pa.idPaseador
        WHERE p.idPaseo = " . intval($this->idPaseo);
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

    public function consultarPendiente()
    {
        return "SELECT count(idPaseo)
                FROM paseo 
                WHERE estado_paseo_idEstadoPaseo = 1 
                AND paseador_idPaseador = " . intval($this->idPaseador);
    }

    public function buscar($filtros)
    {
        $condiciones = [];
        foreach ($filtros as $filtro) {
            $condiciones[] = "(
                p.fecha LIKE '%$filtro%' OR
                pa.nombre LIKE '%$filtro%' OR
                pa.apellido LIKE '%$filtro%' OR
                pe.nombre LIKE '%$filtro%' OR
                d.nombre LIKE '%$filtro%' OR
                d.apellido LIKE '%$filtro%' OR
                e.estado LIKE '%$filtro%'
            )";
        }

        $where = implode(" AND ", $condiciones);

        return "SELECT 
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
                WHERE $where
                ORDER BY p.fecha DESC, p.hora DESC";
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

        $where = implode(" AND ", $condiciones);

        return "SELECT 
                    p.fecha, p.hora, p.tarifa,
                    pe.nombre AS nombre_perro,
                    d.nombre AS nombre_dueño,
                    d.apellido AS apellido_dueño
                FROM paseo p
                JOIN paseo_has_perro pp ON p.idPaseo = pp.paseo_idPaseo
                JOIN perro pe ON pp.perro_idPerro = pe.idPerro
                JOIN dueño d ON pe.dueño_idDueño = d.idDueño
                WHERE p.paseador_idPaseador = " . intval($idPaseador) . "
                AND $where
                ORDER BY p.fecha DESC";
    }

    public function actualizarEstado($nuevoEstado)
    {
        return "UPDATE paseo 
                SET estado_paseo_idEstadoPaseo = $nuevoEstado 
                WHERE idPaseo = " . intval($this->idPaseo);
    }

    public function obtenerEstados()
    {
        return "SELECT idEstadoPaseo, estado 
                FROM estado_paseo 
                ORDER BY idEstadoPaseo";
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
        return "SELECT idPaseo
                FROM paseo
                WHERE fecha = '$fecha'
                  AND hora = '$hora'
                  AND paseador_idPaseador = $idPaseador
                LIMIT 1";
    }

    public function contarPerrosEnPaseo($idPaseo)
    {
        return "SELECT COUNT(*) as cantidad
                FROM paseo_has_perro
                WHERE paseo_idPaseo = $idPaseo";
    }
    public function consultarDueño()
    {
        return "
        SELECT d.idDueño, d.nombre, d.apellido, d.correo
        FROM paseo pa
        JOIN paseo_perro pp ON pa.idPaseo = pp.idPaseo
        JOIN perro p ON pp.idPerro = p.idPerro
        JOIN dueño d ON p.idDueño = d.idDueño
        WHERE pa.idPaseo = '{$this->idPaseo}'
        LIMIT 1
    ";
    }

    public function obtenerPerrosPorDueño($idPaseo, $idDueño)
    {
        return "SELECT 
                perro.idPerro,
                perro.nombre,
                perro.foto,
                perro.tamaño_idTamaño,
                dueño.idDueño,
                dueño.nombre,
                dueño.apellido
            FROM paseo_has_perro
            INNER JOIN perro ON paseo_has_perro.perro_idPerro = perro.idPerro
            INNER JOIN dueño ON perro.dueño_idDueño = dueño.idDueño
            WHERE paseo_has_perro.paseo_idPaseo = $idPaseo
              AND dueño.idDueño = $idDueño";
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

}
