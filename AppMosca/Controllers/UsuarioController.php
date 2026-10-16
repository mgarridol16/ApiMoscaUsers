<?php

require_once __DIR__ . '/../auxMail.php';
require_once __DIR__ . '/../Database/usuarios_dao.php';

class UsuarioController
{
    public static $usuario_DTO;
    public $usuario_DAO;
    public $auxMail;
    public function __construct($usuario_DAO)
    {
        $this->usuario_DAO = $usuario_DAO;
        $this->auxMail = new auxMail();
    }


        public function registrarUser($nameUser, $pass, $email, $edad){
        $usuarioEncontrado = $this->usuario_DAO->obtenerUser($nameUser, md5($pass));

        if ($usuarioEncontrado) {
            http_response_code(409);
            echo json_encode(["Error" => "prueba con otro nombre de usuario"]);

        } else {
            $nuevoUser = new usuarioDTO(null, $nameUser, $email, null, md5($pass), $edad, true);
            $register = $this->usuario_DAO->registerUser($nameUser, $email, md5($pass), $edad);

            if ($register) {
            $this->auxMail->enviarCorreo($email, $nameUser, 'Registro OK', 'Bienvenido a APPMoscaUser, registro completado correctamente');
            http_response_code(200);
            echo json_encode([
                    'message' => 'usuario registrado correctamente',
                    'nameUser' => $nameUser,
                    'email' => $email
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["Error" => "no se ha podido registrar al usuario"]);
            }
        }

        }

        public function loginUser($nameUser, $pass){
            $usuarioEncontrado = $this->usuario_DAO->obtenerUser($nameUser, md5($pass));
            if(!$usuarioEncontrado) {
            http_response_code(409);
            echo json_encode(["Error" => "no se encontro el usuario"]);
            }else{
                $loginUser = $this->usuario_DAO->loginUser($usuarioEncontrado);
                if($loginUser){
                    $this->auxMail->enviarCorreo($usuarioEncontrado->email, $nameUser, 'Login OK', 'Hola de nuevo a APPMoscaUser, te has logueado correctamente ¡A jugar!');
                    http_response_code(200);
                    echo json_encode([
                            'message' => 'usuario logueado correctamente',
                            'id' => $usuarioEncontrado->id,
                            'nombre' => $usuarioEncontrado->user
                        ]);

                }else{
                http_response_code(500);
                echo json_encode(["Error" => "no se ha podido loguear al usuario"]);
                }
            }
        }

        public function consultarTodosUsuarios($nameUser, $pass){
            $usuario = $this->usuario_DAO->obtenerUser($nameUser, md5($pass));
            if($usuario){
                if($usuario->autenticado && $usuario->rol == "ADMIN"){
                $usuarios = $this->usuario_DAO->obtenerTodosUsuarios();

                if($usuarios){
                    http_response_code(200);
                    echo json_encode($usuarios);
                }else{
                    http_response_code(500);
                    echo json_encode(["Error" => "no hay ningun usuario"]);
                }
                }else{
                    http_response_code(404);
                    echo json_encode(["Error" => "no eres admin o no estas logueado"]);
                }
            }else{
                http_response_code(404);
                echo json_encode(["Error" => "no hay ningun usuario logueado"]);
            }
        }

        public function consultarUsuario($nameUser, $pass, $userID){
            $usuario = $this->usuario_DAO->obtenerUser($nameUser, md5($pass));
            if($usuario){
                if($usuario->autenticado && $usuario->rol == "ADMIN"){
                $usuarios = $this->usuario_DAO->obtenerUserByID($userID);

                if($usuarios){
                    http_response_code(200);
                    echo json_encode(['message' => 'usuario encontrado',
                            'id' => $usuarios->id,
                            'nombre' => $usuarios->user,
                            'email' => $usuarios->email,
                            'rol' => $usuarios->rol,
                            'edad' => $usuarios->edad,
                            'autenticado' => $usuarios->autenticado
                            ]);
                }else{
                    http_response_code(500);
                    echo json_encode(["Error" => "no hay ningun usuario"]);
                }
                }else{
                    http_response_code(404);
                    echo json_encode(["Error" => "no eres admin o no estas logueado"]);
                }
            }else{
                http_response_code(404);
                echo json_encode(["Error" => "no hay ningun usuario logueado"]);
            }
        }

        public function delUsuario($nameUser, $pass, $userID){
            $usuario = $this->usuario_DAO->obtenerUser($nameUser, md5($pass));
            if($usuario){
                if($usuario->autenticado && $usuario->rol == "ADMIN"){

                $usuarioAEliminar = $this->usuario_DAO->obtenerUserByID($userID);
                if(!$usuarioAEliminar){
                        http_response_code(404);
                        echo json_encode(["Error" => "El usuario a eliminar no existe"]);
                        return;
                    }

                $usuarioEliminar = $this->usuario_DAO->delUser($userID);


                if($usuarioEliminar){
                    $this->auxMail->enviarCorreo($usuario->email, $nameUser, 'Aviso Importante para ADMIN', 'Acabas de eliminar a un usuario de la base de datos con id: ' . $userID . ' y nombre: ' . $usuarioAEliminar->user);
                    http_response_code(200);
                    echo json_encode(['message' => 'usuario eliminado correctamente']);
                }else{
                    http_response_code(500);
                    echo json_encode(["Error" => "no hay ningun usuario"]);
                }
                }else{
                    http_response_code(404);
                    echo json_encode(["Error" => "no eres admin o no estas logueado"]);
                }
            }else{
                http_response_code(404);
                echo json_encode(["Error" => "no hay ningun usuario logueado"]);
            }
        }

        public function modificarUsuario($nameUser, $pass, $userID, $nuevaEdad, $nuevoNombre, $nuevaPass){
            $usuario = $this->usuario_DAO->obtenerUser($nameUser, md5($pass));
            if($usuario){
                if($usuario->autenticado && $usuario->rol == "ADMIN"){
                    $usuarioParaEditar = $this->usuario_DAO->obtenerUserByID($userID);


                if($usuarioParaEditar){
                    $userMod = $this->usuario_DAO->editUser($userID, $nuevaEdad, $nuevoNombre, md5($nuevaPass));
                    if($userMod){
                        http_response_code(200);
                        echo json_encode(["mensaje" => "usuario editado correctamente"]);

                    }else{
                        http_response_code(404);
                        echo json_encode(["Error" => "no se ha podido modificar el usuario"]);
                    }
                }else{
                    http_response_code(500);
                    echo json_encode(["Error" => "no hay ningun usuario"]);
                }
                }else{
                    http_response_code(404);
                    echo json_encode(["Error" => "no eres admin o no estas logueado"]);
                }
            }else{
                http_response_code(404);
                echo json_encode(["Error" => "no hay ningun usuario logueado"]);
            }
        }

}
