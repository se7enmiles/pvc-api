<?php


class Db
{
    private $host;
    private $username;
    private $database;
    private $password;

    public function __construct($configs)
    {

        $this->host = $configs['host'];
        $this->username = $configs['username'];
        $this->password = $configs['password'];
        $this->database = $configs['database'];

    }

    public function getInstance()
    {
        try {
            $instance = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            // set the PDO error mode to exception
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $instance->exec("set names utf8");

            return $instance;
        } catch (PDOException $e) {
            throw new Exception('Db connection is failed');
        }
    }
}