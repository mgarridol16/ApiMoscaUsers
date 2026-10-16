<?php

require_once __DIR__ . '/Database/database.php';
require_once __DIR__ . '/Models/usuario_DTO.php';
require_once __DIR__ . '/Database/usuarios_dao.php';
require_once __DIR__ . '/Database/mosca_dao.php';
require_once __DIR__ . '/Controllers/MoscaController.php';
require_once __DIR__ . '/Controllers/UsuarioController.php';

header("Content-Type: application/json");

function cargarVariablesEntorno(){
    $envFile = __DIR__ . '/.env';

    if(file_exists($envFile)){
        $lines = file(
            $envFile,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        foreach($lines as $line){
            $line = trim($line);
            $esComentario = strpos($line, '#') === 0;

            $tieneIgual = strpos($line, '=') !== false;

            if(!$esComentario && $tieneIgual){
                $partes = explode('=', $line, 2);

                $nombre = trim($partes[0]);
                $valor = trim($partes[1]);

                putenv($nombre . '=' . $valor);
            }
        }
    }
}

cargarVariablesEntorno();
$db = new Database();
$usuarioDAO = new usuarioDAO($db);
$partidaDAO = new MoscaDAO($db);
$controllerPartida = new ControllerPartida($partidaDAO, $usuarioDAO);
$controllerUsuario = new UsuarioController($usuarioDAO);

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta = trim($_SERVER['REQUEST_URI'], "/");
$parametros = explode("/", $ruta);

try {
    switch ($metodo) {

        case 'POST':
            $datosRecibidos = file_get_contents("php://input");

            if(isset($parametros[0]) && $parametros[0] == 'crear'){
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $tamano = $datosPOST['tamano'];
                    $intentos = $datosPOST['intentos'];
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerPartida->crearTablero($tamano, $intentos, $nameUser, $pass);
                }else{
                     http_response_code(400);
                     echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }elseif(isset($parametros[0]) && ($parametros[0] == '' || $parametros[0] == 'jugar')){
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $casilla = $datosPOST['casilla'];
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerPartida->jugar($casilla, $nameUser, $pass);
                }else{
                     http_response_code(400);
                     echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }elseif(isset($parametros[0]) && $parametros[0] == 'usuarios'){
                 if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $email = $datosPOST['email'];
                    $edad = $datosPOST['edad'];
                    $controllerUsuario->registrarUser($nameUser, $pass, $email, $edad);
                }else{
                     http_response_code(400);
                     echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }elseif(isset($parametros[0]) && $parametros[0] == "login"){
                    if ($datosRecibidos) {
                        $datosPOST = json_decode($datosRecibidos, true);
                        $nameUser = $datosPOST['nameUser'];
                        $pass = $datosPOST['pass'];
                        $controllerUsuario->loginUser($nameUser, $pass);
                    }else{
                     http_response_code(400);
                     echo json_encode(["Error" => "faltan datos en el POST"]);
                    }
            }
            break;

        case 'GET':
            $datosRecibidos = file_get_contents("php://input");
            if (isset($parametros[0]) && $parametros[0] == 'usuarios' && isset($parametros[1])) {
                $userID = $parametros[1];
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerUsuario->consultarUsuario($nameUser, $pass, $userID);
                }else{
                     http_response_code(400);
                     echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }elseif(isset($parametros[0]) && $parametros[0] == 'intentos'){
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerPartida->consultarIntentos($nameUser, $pass);
                }else{
                 http_response_code(400);
                 echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }elseif(isset($parametros[0]) && $parametros[0] == 'usuarios'){
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerUsuario->consultarTodosUsuarios($nameUser, $pass);
                }else{
                 http_response_code(400);
                 echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }
            break;

        case 'DELETE':
            $datosRecibidos = file_get_contents("php://input");
            if (isset($parametros[0]) && $parametros[0] == 'usuarios' && isset($parametros[1])) {
                $userID = $parametros[1];
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerUsuario->delUsuario($nameUser, $pass, $userID);
                }else{
                 http_response_code(400);
                 echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }
            break;

        case 'PUT':
            $datosRecibidos = file_get_contents("php://input");

            if(isset($parametros[0]) && $parametros[0] == 'rendirse'){
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $controllerPartida->rendirse($nameUser, $pass);
                }else{
                    http_response_code(400);
                    echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }elseif(isset($parametros[0]) && $parametros[0] == 'usuarios' && isset($parametros[1])) {
                $userID = $parametros[1];
                if($datosRecibidos){
                    $datosPOST = json_decode($datosRecibidos, true);
                    $nameUser = $datosPOST['nameUser'];
                    $pass = $datosPOST['pass'];
                    $edad = $datosPOST['edad'];
                    $nuevoNombre = $datosPOST['nuevoNombre'];
                    $nuevaPass = $datosPOST['nuevaPass'];
                    $controllerUsuario->modificarUsuario($nameUser, $pass, $userID, $edad, $nuevoNombre, $nuevaPass);
                }else{
                    http_response_code(400);
                    echo json_encode(["Error" => "faltan datos en el POST"]);
                }
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["Error" => "metodo no permitido"]);
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(["Error" => $e->getMessage()]);
}
