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

    public function consultarTodos()
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultarTodos());
        $paseadores = array();
        while ($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], "", $datos[6]);
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
            $this->correo,
            $this->telefono
        );

        $conexion->abrir();

        try {
            $conexion->ejecutar("SELECT idPaseador FROM paseador WHERE correo = '" . $this->correo . "' AND idPaseador != '" . $this->id . "'");
            if ($conexion->filas() > 0) {
                $conexion->cerrar();
                throw new Exception("El correo electrónico ya está registrado en otro paseador");
            }
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
            $paseador = new Paseador($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5]);
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


    public function eliminar()
    {
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->eliminar());
        $resultado = true;

        $conexion->cerrar();


        return $resultado;
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

}
