<?php

class usuarioDTO
{

    public $id;
    public $user;
    public $email;
    public $rol;
    public $pass;
    public $edad;
    public $autenticado;



    public function __construct($id = null, $user = null, $email = null, $rol = null, $pass = null, $edad = null, $autenticado = false)
    {
        $this->id = $id;
        $this->user = $user;
        $this->email = $email;
        $this->rol = $rol;
        $this->pass = $pass;
        $this->edad = $edad;
        $this->autenticado = $autenticado;
    }


    public function __toString()
    {
        return "Usuario(id {$this->id}, user {$this->user}, email {$this->email}, edad {$this->edad}, autenticado {$this->autenticado})";
    }
}
