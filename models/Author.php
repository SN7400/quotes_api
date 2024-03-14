<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // Author Properties
        public $id;
        public $author;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get authors
        public function read() {
            // Create query
            $query = 'SELECT 
                a.id,
                a.author
                FROM
                ' . $this->table . ' a';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get single author
        public function read_single() {
            // Create query
            $query = 'SELECT 
                a.id,
                a.author
                FROM
                ' . $this->table . ' a
                WHERE
                    a.id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(':id', $this->id);

            //Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            if($row) {
                $this->author = $row['author'];
                return true;
            } else {
                return false;
            }
        }

        // Create author
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
            // Another query to get the highest (i.e., most recently created) ID for this author
            $query2 = 'SELECT max(id) FROM ' . $this->table . ' WHERE id in (SELECT id FROM ' . $this->table . ' WHERE author = :author)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($query2);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt2->bindParam(':author', $this->author);

            // Execute query
            if($stmt->execute()) {
                if($stmt2->execute()) {
                    // Retrieve results of SELECT query to get id and author
                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                    // Set properties
                    $this->id = $row['max'];
                    return true;
                }
            }
            
            // Print error if something goes wrong
            if($stmt->error) {
                printf("Error: $s.\n", $stmt->error);
            } elseif ($stmt2->error) {
                printf("Error: $s.\n", $stmt2->error);
            }

            return false;
        }

        // Update author
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                //$stmt->debugDumpParams();
                return true;
            }
            
            // Print error if something goes wrong
            printf("Error: $s.\n", $stmt->error);

            return false;
        }

        // Delete author
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            }
            
            // Print error if something goes wrong
            printf("Error: $s.\n", $stmt->error);

            return false;
        }
    }