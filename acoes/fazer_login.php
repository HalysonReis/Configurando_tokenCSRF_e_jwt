<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require '../vendor/autoload.php';

use app\controler\Login;

if(isset($_POST)){
    $email = htmlspecialchars($_POST['email']);
    $senha = htmlspecialchars($_POST["senha"]);

    ////////// REALIZADO O LOGIN \\\\\\\\\\

    $log = new Login();

    
    $logar = $log->logar($email, $senha);

    /////////// RETORNA UM ARRAY SE O LOGIN FOI COMPLETO
    if(isset($logar->sub)){
        if($logar->acesso == '1'){
            echo json_encode(['location' => '../../app/views/adimin_area.html']);
            http_response_code(200);
        }else if ($logar->acesso == '0'){
            echo json_encode(['location' => '../../app/views/exp_area.html']);
            http_response_code(200);
        }
    }else{
        echo json_encode(['msg' => $logar]);
        http_response_code(404);
    }

}
