<?php
class PaseadorDAO
{
    private $id;
    private $nombre;
    private $apellido;
    private $foto;
    private $correo;
    private $telefono;
    private $clave;
    private $descripcion;
    private $disponibilidad;
    private $estadoPaseador;


    public function __construct($id = 0, $nombre = "", $apellido = "", $foto = "", $correo = "", $telefono = 0, $clave = "", $descripcion = "", $disponibilidad = "", $estadoPaseador = "")
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->foto = $foto;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->descripcion = $descripcion;
        $this->disponibilidad = $disponibilidad;
        $this->estadoPaseador = $estadoPaseador;
    }


    public function autenticar()
    {
        return "SELECT idPaseador FROM paseador WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }


    public function consultar()
    {
        return "SELECT nombre, correo, telefono, foto
                FROM paseador
                WHERE idPaseador = '" . $this->id . "'";
    }
    
    public function consultar2()
    {
        return "SELECT nombre, apellido, correo, telefono, foto, descripcion
            FROM paseador
            WHERE idPaseador = '" . $this->id . "'";
    }



    public function consultarTodos()
    {
        return "SELECT p.idPaseador, p.nombre, p.apellido, p.foto, p.correo, p.telefono, p.descripcion, e.idEstado, e.estado
            FROM paseador p
            JOIN estadopaseador e ON p.idEstado = e.idEstado
            ORDER BY idPaseador";
    }


    public function crear()
    {
        return "INSERT INTO paseador 
    (idPaseador, nombre, apellido, foto, correo, telefono, clave, descripcion, disponibilidad, idEstado)
    VALUES 
    ('" . $this->id . "',
     '" . $this->nombre . "',
     '" . $this->apellido . "',
     '" . $this->foto . "',
     '" . $this->correo . "',
     '" . $this->telefono . "',
     '" . $this->clave . "',
     '" . $this->descripcion . "',
     '" . $this->disponibilidad . "',
     '" . $this->estadoPaseador . "')";
    }



    public function actualizar()
    {
        return "UPDATE paseador SET
            nombre = '" . $this->nombre . "',
            apellido = '" . $this->apellido . "',
            foto = '" . $this->foto . "',
            telefono = '" . $this->telefono . "',
            descripcion = '" . $this->descripcion . "'
            WHERE idPaseador = '" . $this->id . "'";
    }


    public function actualizarClave()
    {
        return "UPDATE paseador SET
            clave = '" . md5($this->clave) . "'
            WHERE idPaseador = '" . $this->id . "'";
    }

    public function cambiarEstado($estadoNuevo)
    {  
        return "UPDATE paseador SET idEstado = $estadoNuevo WHERE idPaseador = " . $this->id;
    }



    public function modificar($filtros)
{
    $condiciones = [];
    foreach ($filtros as $filtro) {
        $condiciones[] = "(p.nombre LIKE '%$filtro%' OR p.descripcion LIKE '%$filtro%' OR p.apellido LIKE '%$filtro%' OR p.correo LIKE '%$filtro%' OR p.telefono LIKE '%$filtro%')";
    }

    $consultaFiltros = implode(" AND ", $condiciones);

    return "SELECT p.idPaseador, p.nombre, p.apellido, p.foto, p.correo, p.telefono, p.descripcion, e.idEstado, e.estado
            FROM paseador p
            JOIN estadopaseador e ON p.idEstado = e.idEstado
            WHERE $consultaFiltros
            ORDER BY p.idPaseador";
}


    public function modificarAceptar($filtros)
    {
        $condiciones = [];

        foreach ($filtros as $filtro) {
            $condiciones[] = "(p.nombre LIKE '%$filtro%' OR p.apellido LIKE '%$filtro%' OR p.correo LIKE '%$filtro%' OR p.telefono LIKE '%$filtro%')";
        }


        $filtroSql = count($condiciones) > 0 ? " AND " . implode(" AND ", $condiciones) : "";

        return "SELECT 
                p.idPaseador, 
                p.nombre, 
                p.apellido, 
                p.foto, 
                p.correo, 
                p.telefono,
                p.descripcion,
                ep.estado AS estadoPaseador
            FROM paseador p
            INNER JOIN estadoPaseador ep ON p.idEstado = ep.idEstado
            WHERE p.idEstado = 3 $filtroSql
            ORDER BY p.nombre";
    }


    public function actualizarEstado()
    {
        return "UPDATE paseador SET idEstado = " . $this->estadoPaseador . " WHERE idPaseador = " . $this->id;
    }



    public function consultarPorEstado()
    {
        return "SELECT 
        p.idPaseador, 
        p.nombre, 
        p.apellido, 
        p.foto, 
        p.correo, 
        p.telefono, 
        p.descripcion, 
        p.disponibilidad, 
        e.estado AS estadoPaseador
    FROM paseador p
    INNER JOIN estadoPaseador e ON p.idEstado = e.idEstado
    WHERE p.idEstado = 3
    ORDER BY p.nombre";
    }


    public function consultarPorEstado2()
    {
        return "SELECT 
        p.idPaseador, 
        p.nombre, 
        p.apellido, 
        p.foto, 
        p.correo, 
        p.telefono, 
        p.descripcion, 
        p.disponibilidad, 
        e.estado AS estadoPaseador
    FROM paseador p
    INNER JOIN estadoPaseador e ON p.idEstado = e.idEstado
    WHERE p.idEstado = 1
    ORDER BY p.nombre";
    }
    public function consultarEstados()
    {
        return "SELECT idEstado, estado AS nombre FROM estadoPaseador";
    }

    public function buscar() {
    return "SELECT 
                p.idPaseador,
                p.Nombre,
                p.Apellido,
                p.Telefono,
                p.Foto,
                p.Descripcion
            FROM paseador p
            WHERE p.idPaseador = '$this->id'";
}

}
