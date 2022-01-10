<?php

class connection{

    private const HOST = 'localhost';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const NAMEDB = 'cinemadb';

    private $connection;

    public function __construct(){

        if(!($this->connection = new mysqli(static::HOST_DB, static::USERNAME, static::PASSWORD, static::NAMEDB))){

            if ($conn->connect_error) {

                echo("Errore");
                die("Connessione fallita: " . $conn->connect_error);

            }

        }

    }

    public function getInstance(){

        return $this->connection;

    }

    public function executeQuery($query){

        return $this->connection->query($query);

    }

    public function __clone() {

        throw new Exception("Non è possibile clonare un singleton.");

    }

    public function disconnect(){

        $connection->close();

    }

}

?>