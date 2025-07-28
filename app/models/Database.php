<?php

namespace app\models;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require '../vendor/autoload.php';
use Dotenv;
use PDO;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, "../../.env");
$dotenv->load();


class Database
{
    //atributos do database
    private $conn;
    private string $local;
    private string $db;
    private string $user;
    private string $password;
    private string $table;


    // metodo construtor que íncia chamando o médoto de conexão com o db 
    function __construct($table = null)
    {
        $this->table = $table;
        $this->conecta();
    }

    function set_conn()
    {
        $this->local = $_ENV['DB_HOST'];
        $this->db = $_ENV['DB_DATABASE'];
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    // se conecta com o db
    private function conecta()
    {

        try {

            $this->set_conn();

            // echo $this->local;

            $this->conn = new PDO("mysql:host=" . $this->local . ";dbname=" . $this->db, $this->user, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $err) {
            die("Conection Failed" . $err->getMessage());
        }
    }

    // médoto para executar o CRUD no db
    // recebe dois parametros, a query e os binds
    public function execute($query, $binds = [])
    {
        try {

            $stmt = $this->conn->prepare($query);
            $stmt->execute($binds);
            return $stmt;
        } catch (\PDOException $err) {
            die("Connection failed" . $err->getMessage());
        }
    }

    // método para inserir no db, tem o parametro $values,
    // que recebe os valores do que serão inseridos
    public function insert($values)
    {
        $fields = array_keys($values);

        $binds = array_pad([], count($fields), '?');

        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        $res = $this->execute($query, array_values($values));

        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    // método de select
    public function select($where = null, $order = null, $limit = null, $fields = "*")
    {
        $where = $where != null ? ' WHERE ' . $where : '';
        $order = $order != null ? ' ORDER BY ' . $order : '';
        $limit = $limit != null ? ' LIMIT ' . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        return $this->execute($query);
    }

    // método update, com parametros $where, $values
    public function update($where, $values)
    {
        $fields = array_keys($values);
        $param = array_values($values);

        $query = "UPDATE " . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;

        $res = $this->execute($query, $param);

        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($where, $status)
    {
        $query = "UPDATE " . $this->table . " SET status_pes = '". $status. "' WHERE " . $where;
        return $this->execute($query) ? true : false;
    }
}

