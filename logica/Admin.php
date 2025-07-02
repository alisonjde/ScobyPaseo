<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/AdminDAO.php");
require_once("logica/Persona.php");

class Admin extends Persona
{
    private $adminDAO;

    public function __construct($id = "", $nombre = "", $apellido = "", $foto = "", $correo = "", $telefono = "", $clave = "")
    {
        parent::__construct($id, $nombre, $apellido, $foto, $correo, $telefono, $clave);
        $this->adminDAO = new AdminDAO($id, $nombre, $apellido, $foto, $correo, $telefono, $clave);
    }

    public function autenticar()
    {
        $conexion = new Conexion();
        $adminDAO = new AdminDAO("", "", "", "", $this->correo, "", $this->clave);
        $conexion->abrir();
        $conexion->ejecutar($adminDAO->autenticar());
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
        $adminDAO = new AdminDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($adminDAO->consultar());
        $datos = $conexion->registro();

        $this->nombre   = $datos[0];
        $this->apellido = $datos[1];
        $this->foto     = $datos[2];
        $this->correo   = $datos[3];
        $this->telefono = $datos[4];

        $conexion->cerrar();
    }
}
