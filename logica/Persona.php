<?php

abstract class Persona {
    protected $id;
    protected $nombre;
    protected $apellido;
    protected $foto;
    protected $correo;
    protected $telefono;
    protected $clave;
    
    public function __construct($id = "", $nombre="", $apellido="", $foto="", $correo="", $telefono="", $clave="") {
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> foto = $foto;
        $this -> correo = $correo;
        $this -> telefono = $telefono;
        $this -> clave = $clave;
    }
    
    public function getId(){
        return $this -> id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getFoto() {
        return $this->foto;
    }
    
    public function getCorreo() {
        return $this->correo;
    }

    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getClave() {
        return $this->clave;
    }
}
?>
