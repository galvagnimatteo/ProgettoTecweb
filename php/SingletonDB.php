<?php

    class SingletonDB{

        private const HOST = 'localhost';
        private const USERNAME = 'root';
        private const PASSWORD = '';
        private const NAMEDB = 'cinemadb';

        private static $instance = null;
        private $connection;

        private function __construct(){

            $connection = new mysqli($HOST_DB, $USERNAME, $PASSWORD, $NAMEDB);

            if($connection->connect_error) {

                echo("Errore");
                die("Connessione fallita: " . $connection->connect_error);

            }

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

            return $connection->query($query);

        }


        public function disconnect(){

            $connection->close();

        }

    }

?>