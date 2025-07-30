<?php


ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require '../vendor/autoload.php';

include '../app/helpers/login.php';

if(confirmaLogin('1')){
    echo json_encode(['acesso' => TRUE]);
    http_response_code(200);
}else{
    echo json_encode(['acesso' => FALSE]);
    http_response_code(404);
}