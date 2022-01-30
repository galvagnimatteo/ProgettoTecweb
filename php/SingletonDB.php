<?php

    class SingletonDB{

        private $HOST = 'localhost';
        private $USERNAME = 'root';
        private $PASSWORD = '';
        private $NAMEDB = 'cinemadb';

        private static $instance = null;
        private $connection;
        private $isConnected = false;

        private function __construct(){

            self::connect();

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

        public function getConnection(){

            global $connection;

            return $connection;

        }

        public function isConnected(){

            global $isConnected;

            return $isConnected;

        }

        public function disconnect(){

            global $connection;
            global $isConnected;

            if($isConnected){

                $connection->close();
                $isConnected = false;

            }

        }

        public function connect(){

            global $HOST;
            global $USERNAME;
            global $PASSWORD;
            global $NAMEDB;
            global $connection;
            global $isConnected;

            if(!$isConnected){

                $connection = new mysqli($this->HOST, $this->USERNAME, $this->PASSWORD, $this->NAMEDB);

                if($connection->connect_error) {

                    echo("Errore");
                    die("Connessione fallita: " . $connection->connect_error);

                }else{

                    $connection->set_charset("utf8");
                    $isConnected = true;

                }

            }

        }

    }

?>