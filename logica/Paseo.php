<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseoDAO.php");
require_once("logica/Paseador.php");
require_once("logica/Dueño.php");
require_once("logica/Perro.php");


class Paseo
{
    private $idPaseo;
    private $tarifa;
    private $fecha;
    private $hora;
    private $idPaseador;
    private $idEstadoPaseo;
    private $paseador;
    private $perro;
    private $direccion;

    public function __construct($idPaseo = "", $tarifa = 0, $fecha = "", $hora = "", $idPaseador = "", $idEstadoPaseo = "", $perro = null, $direccion = "")
    {
        $this->idPaseo = $idPaseo;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->tarifa = $tarifa;
        $this->idPaseador = $idPaseador;
        $this->idEstadoPaseo = $idEstadoPaseo;
        $this->perro = $perro;
        $this->direccion = $direccion;
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
    public function getPaseador()
    {
        return $this->paseador;
    }
    public function getPerro()
    {
        return $this->perro;
    }

    // Métodos de negocio
    public function consultarTodos()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos());

        $paseos = array();
        while ($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[4], $datos[5], "", "", "", "", "", "", "", "");
            $paseo = new Paseo(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $paseador
            );
            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
    }

    public function consultar()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultar());

        $datos = $conexion->registro();
        $this->tarifa = $datos[1];
        $this->fecha = $datos[2];
        $this->hora = $datos[3];
        $this->paseador = new Paseador($datos[4], $datos[5], "", "", "", "", "", "", "", "");

        $conexion->cerrar();
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

    public function consultarPorPaseador($idPaseador)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarPorPaseador($idPaseador));

        $paseos = array();
        while ($datos = $conexion->registro()) {

            $dueño = new Dueño($datos[6], $datos[7], $datos[8]);


            $perro = new Perro($datos[4], $datos[5], null, null, $dueño);


            $paseo = new Paseo(
                $datos[0],
                $datos[3],
                $datos[1],
                $datos[2],
                $idPaseador,
                "",
                $perro
            );

            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
    }


    public function buscarPorPaseador($filtros, $idPaseador)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseoDAO = new PaseoDAO();
        $conexion->ejecutar($paseoDAO->buscarPorPaseador($filtros, $idPaseador));

        $paseos = [];
        while ($datos = $conexion->registro()) {
            $dueño = new Dueño(null, $datos[4], $datos[5]); // nombre y apellido
            $perro = new Perro(null, $datos[3], null, null, $dueño); // nombre
            $paseo = new Paseo(null, $datos[2], $datos[0], $datos[1], $idPaseador, "", $perro);
            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
    }

    public function registrar()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO(0, $this->tarifa, $this->fecha, $this->hora, $this->idPaseador, $this->direccion);

        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->registrar());

        $this->idPaseo = $conexion->obtenerIdInsertado();

        $conexion->cerrar();
        return $this->idPaseo;
    }


    public function asociarPerro($idPerro)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->asociarPerro($this->idPaseo, $idPerro));
        $conexion->cerrar();
    }

    public function buscarPaseoConCupo()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();

        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->buscarPaseoExistente($this->fecha, $this->hora, $this->idPaseador));
        $datos = $conexion->registro();

        $conexion->cerrar();

        if ($datos) {
            $idPaseo = $datos[0];

            if ($this->verificarCupoPaseo($idPaseo)) {
                return $idPaseo;
            }
        }

        return null; 
    }

    private function verificarCupoPaseo($idPaseo)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();

        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->contarPerrosEnPaseo($idPaseo));
        $datos = $conexion->registro();
        $conexion->cerrar();

        if ($datos && $datos[0] < 2) {
            return true;
        }

        return false;
    }
}
