<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PerroDAO.php");
require_once("logica/Dueño.php");

class Perro {
    private $idPerro;
    private $nombre;
    private $foto;
    private $idTamaño;
    private $idDueño;
    
    public function __construct($idPerro = "", $nombre = "", $foto = "", $idTamaño = "", $idDueño = "") {
        $this->idPerro = $idPerro;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->idTamaño = $idTamaño;
        $this->idDueño = $idDueño;
    }
    
    public function getIdPerro() {
        return $this->idPerro;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getFoto() {
        return $this->foto;
    }
    
    public function getIdTamaño() {
        return $this->idTamaño;
    }
    
    public function getIdDueño() {
        return $this->idDueño;
    }
    
    public function consultarCantidad() {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this->idPerro,"","","", $this->idDueño);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO-> consultarCantidad());
        $dato = $conexion->registro();
        $cantidad = $dato[0];
        $conexion->cerrar();
        return $cantidad;
    }

    public function consultarPorDueño(){
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this -> idDueño);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->consultarPorDueño($this -> idDueño));

        $perros = array();
        while($datos = $conexion->registro()) {
            $perro = new Perro(
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                new Dueño($this -> idDueño, $datos[4])
                );
            array_push($perros, $perro);
        }
        
        $conexion->cerrar();
        return $perros;
    }

    public function crear() {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO(
            $this->idPerro,
            $this->nombre,
            $this->foto,
            $this->idTamaño,
            $this->idDueño
            );
        
        $conexion->abrir();

        try{
            $conexion->ejecutar("SELECT idPerro FROM perro WHERE idPerro = '" . $this->idPerro . "'");
            if($conexion->filas() > 0) {
                throw new Exception("El ID del perro ya existe");
            }

            $conexion->ejecutar($perroDAO->crear());
            $resultado = true;

        }catch(Exception){
            $resultado = false;

        }finally{
            $conexion->cerrar();
        }

        return $resultado;

    }

    public function buscar($filtros)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $perroDAO = new PerroDAO();
        $conexion->ejecutar($perroDAO->buscar($filtros));

        $perros = array();
        while (($datos = $conexion->registro())!=null) {
            $dueño=new Dueño($datos[4],$datos[5],$datos[6]);
            $perro = new Perro($datos[0], $datos[1], $datos[2], $datos[3], $dueño);
            array_push($perros, $perro);
        }

        $conexion->cerrar();
        return $perros;
    }
}
?>