<?php
require_once './../clases/respuestas.class.php';
require_once './../clases/pacientes.class.php';
require('./../utils/resJson.php');
require('./../utils/methodNotAllowed.php');


$_respuestas = new respuestas;
$_pacientes = new pacientes;

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method == 'GET') {
    $data = match (true) {
        isset($_GET['id']) => $_pacientes->obtenerPaciente($_GET['id']),
        isset($_GET['page']) => $_pacientes->listaPacientes($_GET['page']),
        default =>  $_pacientes->listaPacientes(),
    };
    // RES
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($data);
} elseif (($method == 'POST' || $method == 'PUT' || $method == 'DELETE')) {
    $postBody = file_get_contents('php://input');
    $datosArray = match ($method) {
        'POST' => $_pacientes->post($postBody),
        'PUT' => $_pacientes->put($postBody),
        'DELETE' => $_pacientes->delete($postBody),
    };
    // RES
    resJson($datosArray);
} else {
    methodNotAllowed($_respuestas);
}
