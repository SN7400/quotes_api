<?php
    class Database {
        private $conn;
        private $dbhost;
        private $dbport;
        private $dbname;
        private $dbuser;
        private $dbpass;

        public function __construct() {
            $this->dbuser = getenv('DBUSER');
            $this->dbpass = getenv('DBPASS');
            $this->dbname = getenv('DBNAME');
            $this->dbhost = getenv('DBHOST');
            $this->dbport = getenv('DBPORT');
        }

        public function connect() {
            if ($this->conn) {
                return $this->conn;
            } else {
                $dsn = "pgsql:host={$this->dbhost};port={$this->dbport};dbname={$this->dbname}";

                try {
                    $this->conn = new PDO($dsn, $this->dbuser, $this->dbpass);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                } catch(PDOException $e) {
                    echo 'Connection Error: ' . $e->getMessage();
                    echo 'DSN: ' . $dsn;
                }
            }
        }
    }