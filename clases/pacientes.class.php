<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once 'token.class.php';



class pacientes extends conexion
{
    private $table = "pacientes";
    private $pacienteid = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";

    public function listaPacientes($pagina = 1)
    {
        $inicio  = 0;
        $cantidad = 100;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM  $this->table  limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerPaciente($id)
    {
        $query = "SELECT * FROM  $this->table WHERE PacienteId = '$id'";
        return parent::obtenerDatos($query);
    }

    public function post($json)
    {
        $_respuestas = new respuestas;
        $_token = new token;
        $datos = json_decode($json, true);

        $_token->validToken($datos, $_respuestas);

        if ($_respuestas->response['status'] !== 'ok') {
            return $_respuestas->response;
        }

        if (!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])) {
            return $_respuestas->err('', 400);
        }

        $this->nombre = $datos['nombre'];
        $this->dni = $datos['dni'];
        $this->correo = $datos['correo'];

        $this->opcionalData($datos);

        $resp = $this->insertarPaciente();

        if (!$resp) {
            return $_respuestas->err('', 500);
        }

        $respuesta = $_respuestas->response;
        $respuesta["result"] = array(
            "pacienteId" => $resp
        );
        return $respuesta;
    }


    private function insertarPaciente()
    {
        $query = "INSERT INTO $this->table (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo) values ('$this->dni','$this->nombre','$this->direccion','$this->codigoPostal','$this->telefono','$this->genero','$this->fechaNacimiento','$this->correo')";

        $resp = parent::nonQueryId($query);
        if ($resp) {
            return $resp;
        }
        return 0;
    }

    public function put($json)
    {
        $_respuestas = new respuestas;
        $_token = new token;
        $datos = json_decode($json, true);

        $_token->validToken($datos, $_respuestas, true);

        if ($_respuestas->response['status'] !== 'ok') {
            return $_respuestas->response;
        }

        $this->pacienteid = $datos['pacienteId'];

        if (isset($datos['nombre'])) {
            $this->nombre = $datos['nombre'];
        }
        if (isset($datos['dni'])) {
            $this->dni = $datos['dni'];
        }
        if (isset($datos['correo'])) {
            $this->correo = $datos['correo'];
        }

        $this->opcionalData($datos);

        $resp = $this->modificarPaciente();

        if (!$resp) {
            return $_respuestas->err('', 500);
        }

        $respuesta = $_respuestas->response;
        $respuesta["result"] = array(
            "pacienteId" => $this->pacienteid
        );
        return $respuesta;
    }

    private function opcionalData($datos)
    {
        if (isset($datos['telefono'])) {
            $this->telefono = $datos['telefono'];
        }
        if (isset($datos['direccion'])) {
            $this->direccion = $datos['direccion'];
        }
        if (isset($datos['codigoPostal'])) {
            $this->codigoPostal = $datos['codigoPostal'];
        }
        if (isset($datos['genero'])) {
            $this->genero = $datos['genero'];
        }
        if (isset($datos['fechaNacimiento'])) {
            $this->fechaNacimiento = $datos['fechaNacimiento'];
        }
    }

    private function modificarPaciente()
    {
        $query = "UPDATE $this->table SET Nombre ='$this->nombre',Direccion = '$this->direccion', DNI = '$this->dni', CodigoPostal = $this->codigoPostal', Telefono = '$this->telefono', Genero = '$this->genero', FechaNacimiento = '$this->fechaNacimiento', Correo = '$this->correo' WHERE PacienteId = '$this->pacienteid'";

        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        }
        return 0;
    }


    public function delete($json)
    {
        $_respuestas = new respuestas;
        $_token = new token;
        $datos = json_decode($json, true);

        $_token->validToken($datos, $_respuestas, true);

        if ($_respuestas->response['status'] !== 'ok') {
            return $_respuestas->response;
        }

        $this->pacienteid = $datos['pacienteId'];

        $resp = $this->eliminarPaciente();

        if (!$resp) {
            return $_respuestas->err('', 500);
        }

        $respuesta = $_respuestas->response;
        $respuesta["result"] = array(
            "pacienteId" => $this->pacienteid
        );
        return $respuesta;
    }


    private function eliminarPaciente()
    {
        $query = "DELETE FROM  $this->table  WHERE PacienteId= '$this->pacienteid'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        }
        return 0;
    }
}
