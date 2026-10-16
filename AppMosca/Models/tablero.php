<?php

//SERIA COMO EL DTO AUNQUE UN DTO EN VERDAD NO DEBE LLEVAR LOGICA
class Tablero
{
    public $id;
    public $idUser;
    public $tablero;
    public $tamano;
    public $intentos;
    public $activo = true;
    public $creado = true;
    public $fecha_create;

    public function __construct($tamano = 10, $intentos = 5, $idUser = null)
    {
        $this->idUser = $idUser;
        $this->tamano = (int)$tamano;
        $this->intentos = (int)$intentos;
        $this->tablero = array_fill(0, $this->tamano, 0);
        $this->colocarMosca();
        $this->fecha_create = date("Y-m-d H:i:s");
        $this->activo = true;
        $this->creado = true;
    }

    // ... resto de tus métodos ...

    public function colocarMosca()
    {
        $pos = rand(0, $this->tamano - 1);
        $this->tablero[$pos] = 1;
    }

    /**
     * Esta función devuelve:
     * -1 -> te has quedado sin intentos
     *  0 -> no te has acercado
     *  1 -> casi le das
     *  2 -> la has cazado
     */
    public function darManotazoTablero($posicion)
    {
        if ($this->intentos <= 0) {
            return -1; // No hay intentos restantes
        }

        $this->intentos--;

        if ($this->tablero[$posicion] == 1) {
            $this->creado = false;
            return 2;  //Cazada
        } else if ($posicion > 0 && $posicion < $this->tamano - 1 && ($this->tablero[$posicion - 1] == 1 || $this->tablero[$posicion + 1] == 1)) {
            return 1; // Casi
        } else {
            return 0; // Ni te acercas
        }
    }

    public function consultarInfoTablero()
    {
        return [
            "intentos" => $this->intentos,
            "tamanio" => $this->tamano,
            "creado" => $this->creado
        ];
    }

public function obtenerPosicionMosca()
{
    for ($i = 0; $i < $this->tamano; $i++) {
        if ($this->tablero[$i] == 1) {
            return $i;
        }
    }
}

    public function __toString()
    {
        $cad = "";
        for ($i = 0; $i < $this->tamano; $i++) {
            $cad .= $this->tablero[$i] . " ";
        }
        return trim($cad);
    }
}


//if(fila = $resultado->fertch_assc)
//probar todo en el index poco a poco no todo de golpe

//PARA DEPURAR
/*
$dao
echo $dao->obtenerTableroActivo();
*/
