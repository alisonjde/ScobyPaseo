<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/DueñoDAO.php");
require_once("logica/Persona.php");

class Dueño extends Persona
{
    private $dueñoDAO;

    public function __construct($id = "", $nombre = "", $apellido = "", $foto = "", $correo = "", $telefono = "", $clave = "")
    {
        parent::__construct($id, $nombre, $apellido, $foto, $correo, $telefono, $clave);
        $this->dueñoDAO = new DueñoDAO($id, $nombre, $apellido, $foto, $correo, $telefono, $clave);
    }

    public function autenticar()
    {
        $conexion = new Conexion();
        $dueñoDAO = new DueñoDAO("", "", "", "", $this->correo, "", $this->clave);
        $conexion->abrir();
        $conexion->ejecutar($dueñoDAO->autenticar());
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
        $dueñoDAO = new DueñoDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($dueñoDAO->consultar());
        $datos = $conexion->registro();

        $this->nombre    = $datos[0];
        $this->apellido  = $datos[1];
        $this->foto      = $datos[2];
        $this->correo    = $datos[3];
        $this->telefono  = $datos[4];
        $this->clave     = $datos[5];

        $conexion->cerrar();
    }


    public function buscar($filtros)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $dueñoDAO = new DueñoDAO();
        $conexion->ejecutar($dueñoDAO->buscar($filtros));

        $dueños = array();
        while (($datos = $conexion->registro())!=null) {
            $dueño = new Dueño($datos[0], $datos[1], $datos[2], "", $datos[3], $datos[4]);
            array_push($dueños, $dueño);
        }

        $conexion->cerrar();
        return $dueños;
    }

    public function insertar()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        if ($conexion->ejecutar($this->dueñoDAO->insertar())) {
            $resultado = $conexion->filasAfectadas();
            $conexion->cerrar();
            return $resultado > 0;
        } else {
            $conexion->cerrar();
            return false;
        }
    }
}
