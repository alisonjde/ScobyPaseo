<?php

class Conexion
{
    private $conexion;
    private $resultado;

    public function abrir()
    {
        $this->conexion = new mysqli("localhost", "root", "", "scoobypaseo");
    }

    public function cerrar()
    {
        $this->conexion->close();
    }

    public function ejecutar($sentencia)
    {
        try {
            $this->resultado = $this->conexion->query($sentencia);
            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }


    public function registro()
    {
        return $this->resultado->fetch_row();
    }

    public function filas()
    {
        if ($this->resultado !== null) {
            return $this->resultado->num_rows;
        } else {
            return 0;
        }
    }

    public function filasAfectadas()
    {
        return $this->conexion->affected_rows;
    }
}
