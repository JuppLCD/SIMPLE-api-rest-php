<?php
require_once '../clases/respuestas.class.php';
require_once '../clases/citas.class.php';
require('./../utils/resJson.php');
require('./../utils/methodNotAllowed.php');


$_respuestas = new respuestas;
$citas = new citas;

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method == 'GET') {
    $data = match (true) {
        isset($_GET['id']) => $citas->obtenerCita($_GET['id']),
        isset($_GET['page']) => $citas->listaCitas($_GET['page']),
        default =>  $citas->listaCitas(),
    };
    // RES
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($data);
} elseif (($method == 'POST' || $method == 'PUT' || $method == 'DELETE')) {
    $postBody = file_get_contents('php://input');
    $datosArray = match ($method) {
        'POST' => $citas->post($postBody),
        'PUT' => $citas->put($postBody),
        'DELETE' => $citas->delete($postBody),
    };
    // RES
    resJson($datosArray);
} else {
    methodNotAllowed($_respuestas);
}
