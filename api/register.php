<?php
require_once './../clases/auth.class.php';
require_once './../clases/respuestas.class.php';
require('./../utils/resJson.php');
require('./../utils/methodNotAllowed.php');

$_auth = new auth;
$_respuestas = new respuestas;

// Register
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $datosArray = $_auth->register($postBody);
    // RES
    resJson($datosArray);
} else {
    // RES
    methodNotAllowed($_respuestas);
}
