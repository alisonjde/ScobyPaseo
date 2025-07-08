<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseadorDAO.php");
require_once("logica/Persona.php");



class Paseador extends Persona
{
    private $descripcion;
    private $disponibilidad;
    private $estadoPaseador;


    public function __construct($id = "", $nombre = "", $apellido = "", $foto = "", $correo = "", $telefono = "", $clave = "", $descripcion = "", $disponibilidad = "", $estadoPaseador = "")
    {
        parent::__construct($id, $nombre, $apellido, $foto, $correo, $telefono, $clave);
        $this->descripcion = $descripcion;
        $this->disponibilidad = $disponibilidad;
        $this->estadoPaseador = $estadoPaseador;
    }


    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getDisponibilidad()
    {
        return $this->disponibilidad;
    }

    public function getEstadoPaseador()
    {
        return $this->estadoPaseador;
    }



    public function autenticar()
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO("", "", "", "", $this->correo, "", $this->clave);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->autenticar());
        if ($conexion->filas() == 1) {
            $this->id = $conexion->registro()[0];
            $conexion->cerrar();
            return true;
        } else {
            $conexion->cerrar();
            return false;
        }
    }

    public function consultar()
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultar());
        $datos = $conexion->registro();
        $this->nombre = $datos[0];
        $this->correo = $datos[1];
        $this->telefono = $datos[2];
        $this->foto = $datos[3];
        $conexion->cerrar();
    }

    public function consultar2()
{
    $conexion = new Conexion();
    $paseadorDAO = new PaseadorDAO($this->id);
    $conexion->abrir();
    $conexion->ejecutar($paseadorDAO->consultar2());
    $datos = $conexion->registro();
    $conexion->cerrar();

    if ($datos == null) {
        return false;
    }

    $this->nombre = $datos[0];
    $this->apellido = $datos[1];
    $this->correo = $datos[2];
    $this->telefono = $datos[3];
    $this->foto = $datos[4];
    $this->descripcion = $datos[5];

    return true; // Éxito
}




    public function consultarTodos()
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultarTodos());
        $paseadores = array();
        while ($datos = $conexion->registro()) {
            $estadoPaseador = new EstadoPaseador($datos[7], $datos[8]);
            $paseador = new Paseador(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $datos[4],
                $datos[5],
                "",
                $datos[6],
                "",
                $estadoPaseador
            );

            array_push($paseadores, $paseador);
        }
        $conexion->cerrar();
        return $paseadores;
    }

    public function crear()
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->foto,
            $this->correo,
            $this->telefono,
            $this->clave,
            $this->descripcion,
            $this->disponibilidad,
            $this->estadoPaseador
        );

        $conexion->abrir();

        try {
            $conexion->ejecutar("SELECT idPaseador FROM paseador WHERE idPaseador = '" . $this->id . "'");
            if ($conexion->filas() > 0) {
                throw new Exception("El ID del paseador ya existe");
            }

            $conexion->ejecutar("SELECT idPaseador FROM paseador WHERE correo = '" . $this->correo . "'");
            if ($conexion->filas() > 0) {
                throw new Exception("El correo electrónico ya está registrado");
            }

            $conexion->ejecutar($paseadorDAO->crear());
            $resultado = true;
        } catch (Exception) {
            $resultado = false;
        } finally {
            $conexion->cerrar();
        }

        return $resultado;
    }


   public function actualizar()
{
    $conexion = new Conexion();
    $paseadorDAO = new PaseadorDAO(
        $this->id,
        $this->nombre,
        $this->apellido,
        $this->foto,
        "",
        $this->telefono,
        "",
        $this->descripcion
    );

    $conexion->abrir();

    try {
        $conexion->ejecutar($paseadorDAO->actualizar());
        $resultado = true;
    } catch (Exception) {
        $resultado = false;
    } finally {
        $conexion->cerrar();
    }
    return $resultado;
}

    public function actualizarClave($nuevaClave)
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id, "", "", $nuevaClave);

        $conexion->abrir();

        try {
            $conexion->ejecutar($paseadorDAO->actualizarClave());
            $resultado = true;
        } catch (Exception) {
            $resultado = false;
        } finally {
            $conexion->cerrar();
        }
        return $resultado;
    }


    public function modificar($filtros)
{
    $conexion = new Conexion();
    $conexion->abrir();
    $paseadorDAO = new PaseadorDAO();
    $conexion->ejecutar($paseadorDAO->modificar($filtros));

    $paseadores = array();
    while (($datos = $conexion->registro()) != null) {
        $estadoPaseador = new EstadoPaseador($datos[7], $datos[8]);

        $paseador = new Paseador(
            $datos[0],
            $datos[1],
            $datos[2],
            $datos[3],
            $datos[4],
            $datos[5],
            "",
            $datos[6],
            "",
            $estadoPaseador
        );

        array_push($paseadores, $paseador);
    }

    $conexion->cerrar();
    return $paseadores;
}


    public function modificarAceptar($filtros)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseadorDAO = new PaseadorDAO();
        $conexion->ejecutar($paseadorDAO->modificarAceptar($filtros));

        $paseadores = array();
        while (($datos = $conexion->registro()) != null) {
            $paseador = new Paseador($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], "", "", "", $datos[6]);
            array_push($paseadores, $paseador);
        }

        $conexion->cerrar();
        return $paseadores;
    }

    public function consultar_estado()
    {
        $conexion = new Conexion();
        $conexion->abrir();

        $paseadorDAO = new PaseadorDAO();
        $conexion->ejecutar($paseadorDAO->consultarPorEstado());

        $paseadores = array();
        while (($datos = $conexion->registro()) != null) {
            $paseador = new Paseador(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $datos[4],
                $datos[5],
                "",
                $datos[6],
                $datos[7],
                $datos[8]
            );
            array_push($paseadores, $paseador);
        }

        $conexion->cerrar();
        return $paseadores;
    }

    public function consultar_estad2()
    {
        $conexion = new Conexion();
        $conexion->abrir();

        $paseadorDAO = new PaseadorDAO();
        $conexion->ejecutar($paseadorDAO->consultarPorEstado2());

        $paseadores = array();
        while (($datos = $conexion->registro()) != null) {
            $paseador = new Paseador(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $datos[4],
                $datos[5],
                "",
                $datos[6],
                $datos[7],
                $datos[8]
            );
            array_push($paseadores, $paseador);
        }

        $conexion->cerrar();
        return $paseadores;
    }


    public function cambiarEstado($estadoNuevo){
    $conexion = new Conexion();
    $paseadorDAO = new PaseadorDAO($this->id);

    $conexion->abrir();
    $conexion->ejecutar($paseadorDAO->cambiarEstado($estadoNuevo));
    $conexion->cerrar();

}



    public function actualizarEstado($estadoNuevo)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseadorDAO = new PaseadorDAO($this->id, "", "", "", "", "", "", "", "", $estadoNuevo);
        $resultado = $conexion->ejecutar($paseadorDAO->actualizarEstado());
        $conexion->cerrar();
        return $resultado;
    }

    public function obtenerEstados()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseadorDAO = new PaseadorDAO();

        $estados = array();

        if ($conexion->ejecutar($paseadorDAO->consultarEstados())) {
            while (($datos = $conexion->registro()) != null) {
                $estados[] = array(
                    "id" => $datos[0],
                    "nombre" => $datos[1]
                );
            }
        }

        $conexion->cerrar();
        return $estados;
    }

    public function buscar() {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->buscar());

        if ($conexion -> filas() == 1) {
            $datos = $conexion->registro();
            $this->id = $datos[0];
            $this->nombre = $datos[1];
            $this->apellido = $datos[2];
            $this->telefono = $datos[3];
            $this->foto = $datos[4];
            $this->descripcion = $datos[5];


            $conexion->cerrar();
            return true;
        }

        $conexion->cerrar();
        return false;
    }

    public function activo($id) {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->activo());
        $datos = $conexion->registro();
        $conexion->cerrar();

    if ($datos && $datos[0] == 1) {
        return true;
    } else {
        return false;
    }
}

}
