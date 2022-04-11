<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';


class auth extends conexion
{
    public function login($json)
    {
        $_respustas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos['usuario']) || !isset($datos["password"])) {
            return $_respustas->err('', 400);
        }
        $usuario = $datos['usuario'];
        $password = parent::encriptar($datos['password']);

        $datosUser = $this->obtenerDatosUsuario($usuario);

        if (!$datosUser) {
            return $_respustas->err("El usuaro $usuario  no existe ", 200);
        } elseif (!($password == $datosUser['Password'])) {
            return $_respustas->err("El password es invalido", 200);
        } elseif (!$datosUser['Estado'] == "Activo") {
            return $_respustas->err("El usuario esta inactivo", 200);
        }

        $verificar  = $this->insertarToken($datosUser['UsuarioId']);

        if (!$verificar) {
            return $_respustas->err("Error interno, No hemos podido guardar", 500);
        }
        $result = $_respustas->response;
        $result["result"] = array(
            "token" => $verificar
        );
        return $result;
    }

    public function register($json)
    {
        $_respustas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos['usuario']) || !isset($datos["password"]) || !isset($datos["passwordConfirm"])) {
            return $_respustas->err('', 400);
        }
        $usuario = $datos['usuario'];
        $password = $datos['password'];
        $passwordConfirm = $datos['passwordConfirm'];

        $userExist = $this->obtenerDatosUsuario($usuario);

        if ($userExist) {
            return $_respustas->err("El usuaro $usuario  ya existe", 200);
        } elseif (!($password == $passwordConfirm)) {
            return $_respustas->err("El password no coincide", 200);
        } elseif (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
            return $_respustas->err("Email no valido", 200);
        } elseif (!preg_match('/^[a-zA-Z0-9\\s]{3,30}$/', $password)) {
            // MIN - 3, Max 30 caracteres
            return $_respustas->err("Password no valido", 200);
        }

        $password = parent::encriptar($datos['password']);

        $newUser = ['usuario' => $usuario, 'password' => $password];

        $userID = $this->crearUsuario($newUser);
        $verificar  = $this->insertarToken($userID);

        if (!$verificar) {
            return $_respustas->err("Error interno, No hemos podido guardar", 500);
        }
        $result = $_respustas->response;
        $result["result"] = array(
            "token" => $verificar
        );
        return $result;
    }

    public function crearUsuario($newUser)
    {
        $userEmail = $newUser['usuario'];
        $password = $newUser['password'];

        $query = "INSERT INTO usuarios (UsuarioId,Usuario,Password,Estado) values ('null','$userEmail','$password', 'Activo')";

        $resp = parent::nonQueryId($query);
        if ($resp) {
            return $resp;
        }
        return 0;
    }

    private function obtenerDatosUsuario($correo)
    {
        $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$correo'";
        $datosUser = parent::obtenerDatos($query);
        if (isset($datosUser[0]["UsuarioId"])) {
            return $datosUser[0];
        } else {
            return 0;
        }
    }

    // REVISAR
    private function insertarToken($usuarioid)
    {
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i", time() + (60 * 5));
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if ($verifica) {
            return $token;
        } else {
            return 0;
        }
    }
}
