<?php

class DueñoDAO
{
    private $id;
    private $nombre;
    private $apellido;
    private $foto;
    private $correo;
    private $telefono;
    private $clave;


    public function __construct($id = "", $nombre = "", $apellido = "", $foto = "", $correo = "", $telefono = 0, $clave = "")
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
        return "SELECT idDueño
                FROM dueño
                WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }

    public function consultar()
    {
        return "SELECT nombre, apellido, foto, correo, telefono, clave FROM dueño WHERE idDueño = '$this->id'";
    }

    public function buscar($filtros){
        $condiciones = [];
        foreach ($filtros as $filtro){
            $condiciones[] = "(nombre LIKE '%$filtro%' OR apellido LIKE '%$filtro%' OR correo LIKE '%$filtro%' OR telefono LIKE '%$filtro%')";
        }

        $consulta = implode("AND",$condiciones);

        $sentencia = "SELECT idDueño, nombre, apellido, correo, telefono
                FROM dueño
                WHERE $consulta";
        return $sentencia;

    }
    public function insertar()
    {
        return "INSERT INTO dueño (idDueño, nombre, apellido, foto, correo, telefono, clave) 
        VALUES ('{$this->id}', '{$this->nombre}', '{$this->apellido}', '{$this->foto}', '{$this->correo}', '{$this->telefono}', MD5('{$this->clave}'))";
    }
}
