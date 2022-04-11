<?php

class conexion
{

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    function __construct()
    {
        $listadatos = $this->datosConexion();
        $this->server = $listadatos['server'];
        $this->user = $listadatos['user'];
        $this->password = $listadatos['password'];
        $this->database = $listadatos['database'];
        $this->port = $listadatos['port'];

        $this->conexion = new mysqli(
            $this->server,
            $this->user,
            $this->password,
            $this->database,
            $this->port
        );
        if ($this->conexion->connect_errno) {
            echo "Error connecting to DB";
            die();
        }
    }

    private function datosConexion()
    {
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config.json");
        $data = json_decode($jsondata, true);
        return $data['conexion'];
    }

    private function convertirUTF8($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    public function obtenerDatos($sqlstr)
    {
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }

    public function nonQuery($sqlstr)
    {
        $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }

    //PARA HACER INSERT
    public function nonQueryId($sqlstr)
    {
        $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if ($filas >= 1) {
            return $this->conexion->insert_id;
        } else {
            return 0;
        }
    }

    protected function encriptar($string)
    {
        return md5($string);
    }
}
