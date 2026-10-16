<?php

require_once __DIR__ . '/../Models/tablero.php';

class ControllerPartida
{
    public $tablero;
    public $moscaDAO;
    public $usuarioDAO;

    public function __construct($moscaDAO, $usuarioDAO)
    {
        $this->moscaDAO = $moscaDAO;
        $this->usuarioDAO = $usuarioDAO;
    }

    public function crearTablero($tamano, $intentos, $nameUser, $pass){
        $usuario = $this->usuarioDAO->obtenerUser($nameUser, md5($pass));

        if(!$usuario){
            http_response_code(404);
            echo json_encode(["Error" => "Usuario no encontrado o credenciales incorrectas"]);
            return;
        }

        if(!$usuario->autenticado || $usuario->rol !== "JUGADOR"){
            http_response_code(403);
            echo json_encode(["Error" => "Jugador no autenticado o rol no permitido"]);
            return;
        }

        $tableroActivo = $this->moscaDAO->obtenerTableroActivo();
        if ($tableroActivo) {
            http_response_code(405);
            echo json_encode(["Error" => "Ya tienes un tablero activo, no puedes crear otro"]);
            return;
        }

        $miObjetoTablero = new Tablero($tamano, $intentos, $usuario->id);
        $nuevoTablero = $this->moscaDAO->crearPartida($miObjetoTablero);

        if ($nuevoTablero) {
            http_response_code(200);
            echo json_encode([
                'message' => 'Tablero creado',
                'tamanio' => $tamano,
                'intentos' => $intentos
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["Error" => "No se ha podido crear el tablero en la base de datos"]);
        }
    }

    public function jugar($casilla, $nameUser, $pass){
        $usuario = $this->usuarioDAO->obtenerUser($nameUser, md5($pass));
        if($usuario){
            if($usuario->autenticado && $usuario->rol == "JUGADOR"){

            $tablero = $this->moscaDAO->obtenerTableroActivo();

            if ($tablero === null) {
                http_response_code(400);
                echo json_encode(["Error" => "No existe una partida activa"]);
            } else {
                if($tablero->intentos > 0){
                    $resultado = $tablero->darManotazoTablero($casilla);
                    $mensaje = null;
                    $actualizada = null;

                    switch($resultado){
                        case 0 : $mensaje = "ni te acercas";
                                $tablero->colocarMosca();
                                $actualizada = $this->moscaDAO->actualizarPartida($tablero->tablero, $tablero->intentos, $tablero->activo);
                                break;
                        case 1: $mensaje = "casi le das";
                                $tablero->colocarMosca();
                                $actualizada = $this->moscaDAO->actualizarPartida($tablero->tablero, $tablero->intentos, $tablero->activo);
                                break;
                        case 2:
                                $mensaje = "cazada";
                                $tablero->activo = false;
                                $actualizada = $this->moscaDAO->actualizarPartida($tablero->tablero, $tablero->intentos, $tablero->activo);
                                break;
                        default : $mensaje = "error en el resultado";
                    }


                    if (!$actualizada) {
                    http_response_code(400);
                    echo json_encode(["Error" => "No se ha podido actualizar el tablero"]);
                    }
                    http_response_code(200);
                    echo json_encode(['resultado' => $mensaje]);
                    }else{
                    http_response_code(400);
                    echo json_encode(["Error" => "No quedan mas intentos no podemos jugar"]);
                        }
                    }
                }else{
                    http_response_code(400);
                    echo json_encode(["Error" => "jugador no auntenticado o rol no permitido"]);
                }
            }
        }

        public function consultarIntentos($nameUser, $pass){
            $usuario = $this->usuarioDAO->obtenerUser($nameUser, md5($pass));
            if($usuario){
                if($usuario->autenticado && $usuario->rol == "JUGADOR"){
                    $tablero = $this->moscaDAO->obtenerTableroActivo();

                if($tablero != null && $tablero->idUser == $usuario->id){
                    $intentos = $this->moscaDAO->getIntentos($tablero->id, $usuario->id);
                    http_response_code(200);
                    echo json_encode(['intentos tablero activo', $intentos]);
                }else{
                http_response_code(400);
                echo json_encode(["Error" => "No existe una partida activa"]);
                }

            }else{
                http_response_code(400);
                echo json_encode(["Error" => "jugador no auntenticado o rol no permitido"]);
            }
        }else{
                http_response_code(400);
                echo json_encode(["Error" => "no existe ese usuario"]);
        }
    }

    public function rendirse($nameUser, $pass){
            $usuario = $this->usuarioDAO->obtenerUser($nameUser, md5($pass));
            if($usuario){
                if($usuario->autenticado && $usuario->rol == "JUGADOR"){
                    $tablero = $this->moscaDAO->obtenerTableroActivo();
                    if($tablero != null && $tablero->idUser == $usuario->id){
                    $actualizada = $this->moscaDAO->rendirTablero($tablero->id, $usuario->id);
                    if($actualizada){
                        $pos = $tablero->obtenerPosicionMosca();
                    http_response_code(200);
                    echo json_encode(["estado" => "RENDIDO", "mosca" => $pos]);
                    }else{
                    http_response_code(400);
                    echo json_encode(["Error" => "el estado de la partida no se actualizo"]);
                    }
                    }else{
                    http_response_code(400);
                    echo json_encode(["Error" => "No existe una partida activa"]);
                    }
                }else{
                http_response_code(400);
                echo json_encode(["Error" => "jugador no auntenticado o rol no permitido"]);
                }
            }
        }
}
