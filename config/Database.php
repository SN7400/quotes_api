<?php
    class Database {
        private $conn;
        private $host;
        private $port;
        private $db_name;
        private $user;
        private $password;

        public function __construct() {
            $this->user = getenv('USER');
            $this->password = getenv('PASSWORD');
            $this->dbname = getenv('DBNAME');
            $this->host = getenv('HOST');
            $this->port = getenv('PORT');
        }

        public function connect() {
            if ($this->conn) {
                return $this->conn;
            } else {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";

                try {
                    $this->conn = new PDO($dsn, $this->user, $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                } catch(PDOException $e) {
                    echo 'Connection Error: ' . $e->getMessage();
                    echo 'DSN: ' . $dsn;
                }
            }
        }
    }