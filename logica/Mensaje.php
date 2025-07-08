<?php

require_once("persistencia/Conexion.php");
require_once("persistencia/MensajeDAO.php");

class Mensaje
{
    private $idMensaje;
    private $paseador;
    private $paseo;
    private $dueño;
    private $tarifaNueva;
    private $estado;

    public function __construct($idMensaje = "", $paseador = "", $paseo = "", $dueño = "", $tarifaNueva = "", $estado = "")
    {
        $this->idMensaje = $idMensaje;
        $this->paseador = $paseador;
        $this->paseo = $paseo;
        $this->dueño = $dueño;
        $this->tarifaNueva = $tarifaNueva;
        $this->estado = $estado;
    }
    public function getIdMensaje()
    {
        return $this->idMensaje;
    }
    public function getPaseador()
    {
        return $this->paseador;
    }
    public function getPaseo()
    {
        return $this->paseo;
    }
    public function getDueño()
    {
        return $this->dueño;
    }
    public function getTarifaNueva()
    {
        return $this->tarifaNueva;
    }
    public function getEstado()
    {
        return $this->estado;
    }

    public function insertar()
    {

        $conexion = new Conexion();
        $conexion->abrir();
        $mensajeDAO = new MensajeDAO("", $this->paseador, $this->paseo, $this->dueño, $this->tarifaNueva, $this->estado);
        $conexion->ejecutar($mensajeDAO->insertar());
        $conexion->cerrar();
    }

    public function existe()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $mensajeDAO = new MensajeDAO("", $this->paseador, $this->paseo);
        $conexion->ejecutar($mensajeDAO->existe());
        if ($conexion->filas() >= 1) {
            $this->idMensaje = $conexion->registro()[0];
            $conexion->cerrar();
            return true;
        } else {
            $conexion->cerrar();
            return false;
        }
    }

    public function notificacion()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $mensajeDAO = new MensajeDAO("", "", "", $this->dueño);

        $conexion->ejecutar($mensajeDAO->notificacion());

        $notificacion = 0;


        if ($conexion->filas() > 0) {
            $fila = $conexion->registro();
            if ($fila !== null && isset($fila[0])) {
                $notificacion = $fila[0];
            }
        }

        $conexion->cerrar();
        return $notificacion;
    }


    public function consultarMensajes()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $mensajeDAO = new MensajeDAO("", "", "", $this->dueño);
        $conexion->ejecutar($mensajeDAO->consultarMensajes());

        $mensajes = array();

        if ($conexion->filas() > 0) {
            while (($datos = $conexion->registro()) != null) {
                $paseador = new Paseador($datos[6], $datos[7], $datos[8]);
                $perro = new Perro($datos[9], $datos[10]);
                $paseo = new Paseo($datos[1], $datos[4], $datos[2], $datos[3], $paseador, "", $perro);
                $dueño = new Dueño($this->dueño);
                $mensaje = new Mensaje($datos[0], $paseador, $paseo, $dueño, $datos[5], $datos[11]);
                array_push($mensajes, $mensaje);
            }
        }

        $conexion->cerrar();
        return $mensajes;
    }

    public function modificarEstado()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $mensajeDAO = new MensajeDAO($this->idMensaje, "", "", "", "", $this->estado);
        $conexion->ejecutar($mensajeDAO->modificarEstado());

        $conexion->cerrar();
    }

    public function modificarTarifa()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $mensajeDAO = new MensajeDAO("", "", $this->paseo, "", $this->tarifaNueva);
        $conexion->ejecutar($mensajeDAO->modificarTarifa());
        $conexion->cerrar();
    }
}
