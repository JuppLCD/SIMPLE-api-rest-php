<?php
require_once 'conexion/conexion.php';

class token extends conexion
{
    private $token = '';
    public function validToken($datos, $clbErr, $needID = false)
    {
        if (!isset($datos['token'])) {
            return $clbErr->err('', 401);
        }

        $this->token = $datos['token'];

        $this->actualizarTokens();
        $arrayToken =  $this->buscarToken();

        if (!$arrayToken) {
            return $clbErr->err('Token invalido o expirado', 401);
        }

        if ($needID) {
            if (!isset($datos['pacienteId'])) {
                return $clbErr->err('', 400);
            }
        }
    }

    private function buscarToken()
    {
        $query = "SELECT  TokenId,UsuarioId,Estado,Fecha from usuarios_token WHERE Token = '$this->token' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if ($resp) {
            return $resp;
        }
        return 0;
    }

    private function actualizarTokens()
    {
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token set Estado = 'Inactivo' WHERE  Fecha < '$date'";
        $verifica = parent::nonQuery($query);
        if ($verifica) {
            return $verifica;
        } else {
            return 0;
        }
    }
}
