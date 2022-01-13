<?php

    class SingletonDB{

        private $HOST = 'localhost';
        private $USERNAME = 'root';
        private $PASSWORD = '';
        private $NAMEDB = 'cinemadb';

        private static $instance = null;
        private $connection;

        private function __construct(){

            global $HOST;
            global $USERNAME;
            global $PASSWORD;
            global $NAMEDB;
            global $connection;

            $connection = new mysqli($this->HOST, $this->USERNAME, $this->PASSWORD, $this->NAMEDB);

            if($connection->connect_error) {

                echo("Errore");
                die("Connessione fallita: " . $connection->connect_error);

            }

            $connection->set_charset("utf-8");

        }

        public static function getInstance(){

            if (!self::$instance){

                self::$instance = new self();

            }

            return self::$instance;

        }

        public function __clone() {

            throw new Exception("Non è possibile clonare un singleton.");

        }

        public function executeQuery($query){

            global $connection;

            return $connection->query($query);

        }


        public function disconnect(){

            global $connection;

            $connection->close();

        }

    }

?>