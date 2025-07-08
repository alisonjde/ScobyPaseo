<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseoDAO.php");




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
        $this->tarifa = $tarifa;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->idPaseador = $idPaseador;
        $this->idEstadoPaseo = $idEstadoPaseo;
        $this->perro = $perro;
        $this->direccion = $direccion;
    }

    // Getters
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
    public function getPaseador()
    {
        return $this->paseador;
    }

    public function getPerro()
    {
        return $this->perro;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }


    public function consultarTodos($id, $rol, $filtros = [])
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos($id, $rol, $filtros));

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

    public function consultarTodos2($id = "", $rol = "")
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos2($id, $rol));

        $paseos = array();
        while ($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[4], $datos[5], $datos[6], "", "", "", "", "", "", "");
            $dueño = new Dueño($datos[10], $datos[11], $datos[12]);
            $perro = new Perro($datos[7], $datos[8], $datos[9], "", $dueño);
            $estadoPaseo = new EstadoPaseo($datos[13], $datos[14]);

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
        $this->paseador = new Paseador($datos[4], $datos[5], $datos[6], "", "", "", "", "", "", "");
        $this->direccion = $datos[7];


        $conexion->cerrar();
    }


    public function consultarPendiente()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO("", "", "", "", $this->idPaseador);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarPendiente());

        $datos = $conexion->registro();
        $conexion->cerrar();
        return $datos[0];
    }



    public function buscarPorPaseador($filtros, $idPaseador)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $paseoDAO = new PaseoDAO();
        $conexion->ejecutar($paseoDAO->buscarPorPaseador($filtros, $idPaseador));

        $paseos = [];
        while ($datos = $conexion->registro()) {
            $dueño = new Dueño(null, $datos[4], $datos[5]);
            $perro = new Perro(null, $datos[3], null, null, $dueño);
            $paseo = new Paseo(null, $datos[2], $datos[0], $datos[1], $idPaseador, "", $perro);
            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
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
            $dueño = new Dueño($datos[9], $datos[10], $datos[11]);
            $perro = new Perro($datos[7], $datos[8], "", "", $dueño);
            $estadoPaseo = new EstadoPaseo($datos[12], $datos[13]);
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

    public function actualizarEstado($nuevoEstado)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->actualizarEstado($nuevoEstado));
        $conexion->cerrar();
    }

    public function obtenerEstados()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->obtenerEstados());

        $estados = array();
        while ($datos = $conexion->registro()) {
            $estado = new EstadoPaseo($datos[0], $datos[1]);
            array_push($estados, $estado);
        }

        $conexion->cerrar();
        return $estados;
    }

    public function buscarPaseoConCupo()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->buscarPaseoExistente($this->fecha, $this->hora, $this->idPaseador));
        $datos = $conexion->registro();
        $conexion->cerrar();

        if ($datos && $this->verificarCupoPaseo($datos[0])) {
            return $datos[0];
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

        return ($datos && $datos[0] < 2);
    }

    public function consultarDueño()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarDueño());

        $dueño = null;
        if ($conexion->filas() > 0) {
            $datos = $conexion->registro();
            $dueño = new Dueño($datos[0], $datos[1], $datos[2], $datos[3]);
        }

        $conexion->cerrar();
        return $dueño;
    }

    public function obtenerPerrosPorDueño($idDueño)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->obtenerPerrosPorDueño($this->idPaseo, $idDueño));

        $perros = [];

        while (($datos = $conexion->registro()) !== null) {
            $dueño = new Dueño($datos[4], $datos[5], $datos[6]);
            $perro = new Perro($datos[0], $datos[1], $datos[2], $datos[3], $dueño);
            $perros[] = $perro;
        }

        $conexion->cerrar();
        return $perros;
    }

    public function consultarPorPaseador($idPaseador)
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarPorPaseador($idPaseador));

        $paseos = [];
        while ($datos = $conexion->registro()) {
            $paseador = new Paseador($datos[4], $datos[5], $datos[6]);
            $perro = new Perro($datos[7], $datos[8]);
            $estado = new EstadoPaseo($datos[9], $datos[10]);
            $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3], $paseador, $estado, $perro);
            $paseos[] = $paseo;
        }

        $conexion->cerrar();
        return $paseos;
    }

    public function consultarTarifa()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO("", "", "", "", $this->idPaseador);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTarifa());

        $paseos = array();
        while ($datos = $conexion->registro()) {
            $dueño = new Dueño($datos[4], $datos[5], $datos[6]);
            $perro = new Perro($datos[7], $datos[8], $datos[9], "", $dueño);
            $paseo = new Paseo(
                $datos[0],  // idPaseo
                $datos[3],  // tarifa
                $datos[1],  // fecha
                $datos[2],  // hora
                $this->idPaseador,  // usar el paseador actual
                "",         // idEstadoPaseo
                $perro
            );
            array_push($paseos, $paseo);
        }

        $conexion->cerrar();
        return $paseos;
    }

    public function cancelarPaseo()
    {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->idPaseo);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->cancelarPaseo());
        $resultado = $conexion->filasAfectadas();
        $conexion->cerrar();
        return $resultado > 0;
    }
}
