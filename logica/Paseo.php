<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseoDAO.php");
require_once("logica/Paseador.php");

class Paseo
{
    private $idPaseo;
    private $tarifa;
    private $fecha;
    private $hora;
    private $idPaseador;
    private $idEstadoPaseo;
    private $perro;

    public function __construct($idPaseo = "", $tarifa = 0, $fecha = "", $hora = "", $idPaseador = "", $idEstadoPaseo = "", $perro="")
    {
        $this->idPaseo = $idPaseo;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->tarifa = $tarifa;
        $this->idPaseador = $idPaseador;
        $this->idEstadoPaseo = $idEstadoPaseo;
        $this->perro = $perro;
        }

    public function getIdPaseo()
    {
        return $this->idPaseo;
    }

    public function getTarifa()
    {
        return $this->tarifa;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getHora()
    {
        return $this->hora;
    }

    public function getIdPaseador()
    {
        return $this->idPaseador;
    }
    public function getEstadoPaseo()
    {
        return $this->idEstadoPaseo;
    }
    public function getPerro()
    {
        return $this->perro;
    }
    public function consultarTodos($id, $rol)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos($id, $rol));

        $paseos = array();
        while ($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[4], $datos[5], $datos[6], "", "", "", "", "", "", "");
            $dueño = new Dueño($datos[9],$datos[10],$datos[11]);
            $perro = new Perro($datos[7],$datos[8],"","",$dueño);
            $estadoPaseo = new EstadoPaseo($datos[12],$datos[13]);
            $paseo = new Paseo(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $paseador,
                $estadoPaseo,
                $perro
            );
            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
    }

    public function consultarPendiente()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO("", "", "", "", $this->idPaseador);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarPendiente());

        $datos = $conexion->registro();
        $cantidad = $datos[0];

        $conexion->cerrar();
        return $cantidad;
    }

    public function buscar($filtros)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseoDAO = new PaseoDAO();
        $conexion->ejecutar($paseoDAO->buscar($filtros));

        $paseos = array();
         while ($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[4], $datos[5], $datos[6], "", "", "", "", "", "", "");
            $dueño = new Dueño($datos[9],$datos[10],$datos[11]);
            $perro = new Perro($datos[7],$datos[8],"","",$dueño);
            $estadoPaseo = new EstadoPaseo($datos[12],$datos[13]);
            $paseo = new Paseo(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $paseador,
                $estadoPaseo,
                $perro
            );
            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
    }
}
