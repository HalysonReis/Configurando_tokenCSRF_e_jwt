<?php
namespace app\controler;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require '../vendor/autoload.php';

use PDO;
use app\models\Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
if(isset($_SESSION)){
    session_destroy();
}

class Login {
    public function logar($email, $senha){
        $db = new Database('login');
        $user = $db->select('email = "'.$email.'"')->fetch(PDO::FETCH_ASSOC);
        if ($user){
            if($user['senha'] == $senha){

                $payload = [
                    'iss' => 'oi bb',
                    'sub' => $user['id_login'],
                    'exp' => time() + 30,
                    'iat' => time(),
                    'acesso' => $user['nivel_acesso'],
                ];

                $this->encodejwt($payload);

                $jwt = JWT::decode($_SESSION["tolkenLogin"], new Key($_ENV['PASS_SECRET_MOST_SECRET'], 'HS256'));
                return $jwt;

            }else{
                return 'Senha incorreta';
            }
        }else{
            return 'Email incorreto';
        }
    }

    public function encodejwt($payload){
        $jwt = JWT::encode($payload, $_ENV['PASS_SECRET_MOST_SECRET'], 'HS256');

        session_start();
        $_SESSION["tolkenLogin"] = $jwt;
    }

    public static function decodejwt(){
        $tolkien = isset($_SESSION['tokenLogin']) ? $_SESSION['tokenLogin'] : false;
        if($tolkien){
            $jwt = JWT::decode($_SESSION['tokenLogin'], new Key($_ENV['PASS_SECRET_MOST_SECRET'], 'HS256'));
            return $jwt;
        }else {
            return 'invalid';
        }

    }
}


