<?php

class AdminDAO
{
    private $id;
    private $nombre;
    private $apellido;
    private $foto;
    private $correo;
    private $telefono;
    private $clave;

    public function __construct($id = 0, $nombre = "", $apellido = "", $foto = "", $correo = "", $telefono = 0, $clave = "")
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->foto = $foto;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
    }

    public function autenticar()
    {
        return "SELECT idAdmin
                FROM admin
                WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }

    public function consultar()
    {
        return "SELECT nombre, apellido, foto, correo, telefono
            FROM admin
            WHERE idAdmin = '" . $this->id . "'";
    }
}
