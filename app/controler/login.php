<?php
namespace app\controler;

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

require '../vendor/autoload.php';

use PDO;
use Dotenv;
use app\models\Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, "../../.env");
$dotenv->load();

class Login {
    public function logar($email, $senha){
        try {
            $db = new Database('login');
            $user = $db->select('email = "'.$email.'"')->fetch(PDO::FETCH_ASSOC);
            if ($user){
                if($user['senha'] == $senha){
    
                    $payload = [
                        'iss' => 'bosquedapaz',
                        'sub' => $user['id_login'],
                        'exp' => time() + 30,
                        'iat' => time(),
                        'acesso' => $user['nivel_acesso'],
                        'email' => $user['email']
                    ];
    
                    $createJWT = $this->encodejwt($payload);

                    if(!$createJWT){
                        return [
                            'sucess' => FALSE,
                            'msg' => 'Login inválido'
                        ];
                    }else{
                        return [
                            'sucess' => TRUE,
                            'acesso' => $user['nivel_acesso'],
                            $createJWT
                        ];
                    }
                }else{
                    return [
                        'sucess' => FALSE,
                        'msg' => 'Senha incorreta'
                    ];
                }
            }else{
                return [
                    'sucess' => FALSE,
                    'msg' => 'E-mail incorreto'
                ];
            }
        } catch (\Throwable $th) {
            return [
                'sucess' => FALSE,
                'msg' => 'Login inválido'
            ];
        }
    }

    public function encodejwt($payload){
        try {
            $jwt = JWT::encode($payload, $_ENV['PASS_SECRET_MOST_SECRET'], 'HS256');
    
            if(!isset($_SESSION)){
                session_start();
            }
    
            if(isset($_SESSION['tolkenLogin'])){
                unset($_SESSION['tolkenLogin']);
            }
    
            $_SESSION["tolkenLogin"] = $jwt;
            return $_SESSION["tolkenLogin"];
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    public static function decodejwt(){
        try {
            if(!isset($_SESSION)){
                session_start();
            }
            if(!isset($_SESSION['tolkenLogin'])){
                return [
                    'sucess' => FALSE,
                    'msg' => 'Usuário não logado',
                ];
            }
            else {
                $jwt = JWT::decode($_SESSION['tolkenLogin'], new Key($_ENV['PASS_SECRET_MOST_SECRET'], 'HS256'));
                return [
                    'sucess' => TRUE,
                    'jwt' => $jwt
                ];
            }
        } catch (\Throwable $th) {
            return [
                'sucess' => FALSE,
                'msg' => 'Tolken inválido'
            ];
        }
    }

    public static function validaLogin($acesso){
        $dadosJwt = self::decodejwt();
        if($dadosJwt['sucess']){
            if($acesso == $dadosJwt['jwt']->acesso){
                return TRUE;
            }else {
                return FALSE;
            }
        }else {
            return FALSE;
        }
    }
}


