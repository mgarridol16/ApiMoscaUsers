<?php

class MoscaDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function actualizarPartida($tablero, $intentos, $activo)
    {

        $conexion =  $this->db->connect();
        $tab = $this->obtenerTableroActivo();

        if ($tab) {
            $stmt = $conexion->prepare("UPDATE partida SET tablero = ?, intentos = ?, activo = ? WHERE id = ?");

            $tableroString = implode(',', $tablero);
            $intentosValor = (int)$intentos;
            $activoValor = (int)$activo;
            $idValor = (int)$tab->id;

            $stmt->bind_param("siii", $tableroString, $intentosValor, $activoValor, $idValor);
            $ok = $stmt->execute();

            $stmt->close();
            $conexion->close();
            return $ok;

        } else {
            $conexion->close();
            return false;
        }
    }


    /**
     * Obtiene el tablero activo de la base de datos.
     * Si no existe devuelve null.
     */
    public function obtenerTableroActivo()
    {
        $conexion = $this->db->connect();
        $query = "SELECT * FROM partida WHERE activo = 1";
        $resultado = $conexion->query($query);
        $tab = null;

        if ($fila = $resultado->fetch_assoc()) {
            $tab = new Tablero();
            $tab->id = $fila["id"];
            $tab->idUser = $fila["idUser"];
            $tab->tablero = explode(',', $fila["tablero"]);
            $tab->tamano = count($tab->tablero);
            $tab->intentos = $fila["intentos"];
            $tab->activo = $fila["activo"];
            $tab->fecha_create = $fila["fecha_create"];
        }
        $conexion->close();

        return $tab;
    }

    public function obtenerTablero($id)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare("SELECT * FROM partida WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tab = null;

        if ($fila = $resultado->fetch_assoc()) {
            $tab = new Tablero();
            $tab->id = $fila["id"];
            $tab->tablero = explode(',', $fila["tablero"]);
            $tab->tamano = count($tab->tablero);
            $tab->intentos = $fila["intentos"];
            $tab->activo = $fila["activo"];
        }

        $stmt->close();
        $conexion->close();

        return $tab;
    }

    public function crearPartida($tab)
    {

        $conexion =  $this->db->connect();

        $tableroStr = implode(',', $tab->tablero);

        $stmt = $conexion->prepare("INSERT INTO partida (idUser, tablero, intentos, activo, fecha_create) VALUES (?, ?, ?, ?, ?)");

        $activoValor = (int)$tab->activo;
        $stmt->bind_param("isiss", $tab->idUser, $tableroStr, $tab->intentos, $activoValor, $tab->fecha_create);

        $ok = $stmt->execute();

        $stmt->close();
        $conexion->close();
        return $ok;
        // La partida no existe o no está activa
    }

    public function getIntentos($id, $idUser): int {
        $conexion = $this->db->connect();

        $stmt = $conexion->prepare("SELECT intentos FROM partida WHERE id = ? AND idUser = ?");
        $stmt->bind_param("ii", $id, $idUser);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $intentos = 0;

        if($fila = $resultado->fetch_assoc()){
            $intentos = $fila["intentos"];
        }

        $stmt->close();
        $conexion->close();
        return $intentos;
    }

    public function rendirTablero($idTablero, $idUser){
        $conexion =  $this->db->connect();
        $tab = $this->obtenerTableroActivo();
        if ($tab) {
            $stmt = $conexion->prepare("UPDATE partida SET activo = ? WHERE id = ? AND idUser = ?");
            $activoFalse = 0;

            $stmt->bind_param("iii", $activoFalse, $idTablero, $idUser);
            $ok = $stmt->execute();

            $stmt->close();
            $conexion->close();
            return $ok;



        } else {
            $conexion->close();
            return false; // La partida no existe o no está activa
        }
    }


}
