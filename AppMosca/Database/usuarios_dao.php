<?php

class usuarioDAO
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerUser($nameUser, $pass)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE user = ? AND pass = ?");
        $stmt->bind_param("ss", $nameUser, $pass);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = null;

        if ($fila = $resultado->fetch_assoc()) {
            $usuario = new usuarioDTO();
            $usuario->id = $fila["id"];
            $usuario->user = $fila["user"];
            $usuario->email = $fila["email"];
            $usuario->rol = $fila["rol"];
            $usuario->pass = $fila["pass"];
            $usuario->edad = $fila["edad"];
            $usuario->autenticado = $fila["autenticado"];
        }

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    public function loginUser($usuarioEncontrado)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare("UPDATE usuarios SET autenticado = ? WHERE id = ?");
        $activo = 1;
        $id = $usuarioEncontrado->id;
        $stmt->bind_param("ii", $activo, $id);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function registerUser($nameUser, $email, $pass, $edad)
    {
        /*
        $this->id = $id;
        $this->user = $user;
        $this->email = $email;
        $this->rol = $rol;
        $this->pass = $pass;
        $this->edad = $edad;
        $this->autenticado = $autenticado;
        */

        $conexion = $this->db->connect();
        $stmt = $conexion->prepare("INSERT INTO usuarios (user, email, rol, pass, edad, autenticado) VALUES (?, ?, ?, ?, ?, ?)");
        $user = $nameUser;
        $clave = $pass;
        $mail = $email;
        $edadUsuario = $edad;
        $activo = true;
        $rol = "JUGADOR";
        $stmt->bind_param("ssssii", $user, $mail, $rol, $clave, $edadUsuario, $activo);

        $ok = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ok;
    }

    public function obtenerUserByID($userID) //EDITAR
    {
        $userID = (int)$userID;
        $conexion = $this->db->connect();

        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $usuario = null;

        if ($fila = $resultado->fetch_assoc()) {
            $usuario = new usuarioDTO();
            $usuario->id = $fila["id"];
            $usuario->user = $fila["user"];
            $usuario->email = $fila["email"];
            $usuario->rol = $fila["rol"];
            $usuario->pass = $fila["pass"];
            $usuario->edad = $fila["edad"];
            $usuario->autenticado = $fila["autenticado"];
        }
        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    public function obtenerTodosUsuarios()
    {

        $conexion = $this->db->connect();
        $usuarios = [];
        $query = "SELECT * FROM usuarios";

        $resultado = $conexion->query($query);

        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = new usuarioDTO($fila["id"], $fila["user"], $fila["email"], $fila["rol"], $fila["pass"], $fila["edad"], $fila["autenticado"]);
        }
        $conexion->close();
        return $usuarios;
    }

    public function delUser($userId)
    {

        $conexion = $this->db->connect();
        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        $id = $userId;
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();

        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function editUser($userId, $nuevaEdad, $nuevoNombre, $newPass)
    {
        $usuarioaEditar = $this->obtenerUserByID($userId);

        if (!$usuarioaEditar) {
            return false;
        }
            //EDITAR Y TERMINAR
        $conexion = $this->db->connect();
        $id = $usuarioaEditar->id;

        if ($newPass !== null && $nuevaEdad !== null && $nuevoNombre !== null) {
            $stmt = $conexion->prepare("UPDATE usuarios SET pass = ?, edad = ?, user = ? WHERE id = ?");
            $stmt->bind_param("sisi", $newPass, $nuevaEdad, $nuevoNombre, $id);

        } else if ($newPass === null && $nuevaEdad !== null && $nuevoNombre === null) {
            $stmt = $conexion->prepare("UPDATE usuarios SET edad = ? WHERE id = ?");
            $stmt->bind_param("ii", $nuevaEdad, $id);

        } else if ($newPass !== null && $nuevaEdad === null && $nuevoNombre === null) {
            $stmt = $conexion->prepare("UPDATE usuarios SET pass = ? WHERE id = ?");
            $stmt->bind_param("si", $newPass, $id);

        } else if ($newPass === null && $nuevaEdad === null && $nuevoNombre !== null) {
            $stmt = $conexion->prepare("UPDATE usuarios SET user = ? WHERE id = ?");
            $stmt->bind_param("si", $nuevoNombre, $id);

        } else if ($newPass === null && $nuevaEdad !== null && $nuevoNombre !== null) {
            $stmt = $conexion->prepare("UPDATE usuarios SET edad = ?, user = ? WHERE id = ?");
            $stmt->bind_param("isi", $nuevaEdad, $nuevoNombre, $id);
        }

        $ok = false;

        if ($stmt !== null) {
            $ok = $stmt->execute();
            $stmt->close();
        }

        $conexion->close();
        return $ok;
    }
}

//CORREGUIR LO DE LOS SELECTS
