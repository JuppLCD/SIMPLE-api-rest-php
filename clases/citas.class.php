<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';
require_once 'token.class.php';

class citas extends conexion
{
    private $table = 'citas';
    private $pacienteId = "";
    private $fecha = "";
    private $horaInicio = "";
    private $horaFIn = "";
    private $motivo = "";
    private $estado = "";
    private $citaID = "";

    public function listaCitas($pagina = 1)
    {
        $inicio  = 0;
        $cantidad = 100;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT citas.CitaId, pacientes.Nombre, pacientes.Correo, citas.Fecha, citas.HoraInicio, citas.HoraFIn,citas.Estado, citas.Motivo FROM $this->table INNER JOIN pacientes ON citas.PacienteId = pacientes.PacienteId limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerCita($id)
    {
        $query = "SELECT citas.CitaId, pacientes.Nombre, pacientes.Correo, citas.Fecha, citas.HoraInicio, citas.HoraFIn,citas.Estado, citas.Motivo FROM $this->table INNER JOIN pacientes ON citas.PacienteId = pacientes.PacienteId WHERE citas.CitaId ='$id'";
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

        if (!isset($datos['pacienteId']) || !isset($datos['fecha']) || !isset($datos['horaInicio']) || !isset($datos['motivo'])) {
            return $_respuestas->err('', 400);
        }
        if (strtotime($datos['horaInicio']) === false) {
            return $_respuestas->err('La hora de inicio no esta en un formato correcto', 400);
        }

        $this->pacienteId = $datos['pacienteId'];
        $this->fecha = $datos['fecha'];
        $this->horaInicio = $datos['horaInicio'];
        $this->motivo = $datos['motivo'];

        // OPCIONALES
        $this->estado = $datos['estado'] ?? 'Sin Confirmar';
        $this->horaFIn = $datos['horaFIn'] ?? $this->obtenerHoraFin();

        $resp = $this->insertarCita();

        if (!$resp) {
            return $_respuestas->err('', 500);
        }

        $respuesta = $_respuestas->response;
        $respuesta["result"] = array(
            "citaId" => $resp
        );
        return $respuesta;
    }

    private function obtenerHoraFin()
    {
        $inicio = $this->horaInicio;
        $tiempoEstimadoFinalizacion = 30;
        $segundos_horaInicial = strtotime($inicio);
        $segundos_tiempoEstimadoFinalizacion = $tiempoEstimadoFinalizacion * 60;
        $nuevaHora = date("H:i", $segundos_horaInicial + $segundos_tiempoEstimadoFinalizacion);

        return $nuevaHora;
    }

    private function insertarCita()
    {
        $query = "INSERT INTO $this->table (`CitaId`, `PacienteId`, `Fecha`, `HoraInicio`, `HoraFIn`, `Estado`, `Motivo`) values ('NULL','$this->pacienteId','$this->fecha','$this->horaInicio','$this->horaFIn','$this->estado','$this->motivo')";

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

        if (!isset($datos['citaID'])) {
            return $_respuestas->err('', 400);
        }
        $this->citaID = $datos['citaID'];

        if (isset($datos['pacienteId'])) {
            $this->fecha = $datos['pacienteId'];
        }
        if (isset($datos['fecha'])) {
            $this->fecha = $datos['fecha'];
        }
        if (isset($datos['horaInicio'])) {
            $this->horaInicio = $datos['horaInicio'];
        }
        if (isset($datos['motivo'])) {
            $this->motivo = $datos['motivo'];
        }
        if (isset($datos['estado'])) {
            $this->estado = $datos['estado'];
        }
        if (isset($datos['horaFIn'])) {
            $this->horaFIn = $datos['horaFIn'];
        }

        $resp = $this->modificarCita();

        if (!$resp) {
            return $_respuestas->err('', 500);
        }

        $respuesta = $_respuestas->response;
        $respuesta["result"] = array(
            "citaId" => $this->citaID
        );
        return $respuesta;
    }

    private function modificarCita()
    {
        $query = "UPDATE $this->table SET PacienteId ='$this->pacienteId',Fecha = '$this->fecha', HoraInicio = '$this->horaInicio', HoraFIn = $this->horaFIn', Estado = '$this->estado', Motivo = '$this->motivo' WHERE CitaId = '$this->citaID'";

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

        if (!isset($datos['citaID'])) {
            return $_respuestas->err('', 400);
        }
        $this->citaID = $datos['citaID'];

        $resp = $this->eliminarPaciente();

        if (!$resp) {
            return $_respuestas->err('', 500);
        }

        $respuesta = $_respuestas->response;
        $respuesta["result"] = array(
            "citaID" => $this->citaID
        );
        return $respuesta;
    }

    private function eliminarPaciente()
    {
        $query = "DELETE FROM  $this->table  WHERE CitaId= '$this->citaID'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        }
        return 0;
    }
}
