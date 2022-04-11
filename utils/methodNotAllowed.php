<?php
function methodNotAllowed($objRes)
{
    header('Content-Type: application/json');
    $datosArray = $objRes->err('', 405);
    echo json_encode($datosArray);
}
