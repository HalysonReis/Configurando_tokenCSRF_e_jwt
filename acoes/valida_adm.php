<?php

require '../vendor/autoload.php';

use app\controler\Login;
session_start();

if(isset($_SESSION['tolkenLogin'])){
    $existe = Login::decodejwt();
    if($existe === 'invalid'){
        echo 'a';
    }else{
        echo 'b';
    }
}else{
    print_r($_SESSION);
}