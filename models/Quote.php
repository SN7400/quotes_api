<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';
        private $table2 = 'authors';
        private $table3 = 'categories';

        // Quote Properties
        public $id;
        public $quote;
        public $author;
        public $category;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get quotes
        public function read() {
            // Create query
            $query = 'SELECT 
                q.id,
                q.quote,
                a.author,
                c.category
                FROM
                ' . $this->table . ' q
                INNER JOIN ' . $this->table2 . ' a ON q.author_id = a.id
                INNER JOIN ' . $this->table3 . ' c ON q.category_id = c.id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get single quote
        public function read_single() {
            // Create query
            $query = 'SELECT 
                a.id,
                a.quote
                FROM
                ' . $this->table . ' a
                WHERE
                    a.id = ?;';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            //Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            if($row) {
                $this->quote = $row['quote'];
            }
        }

        // Create quote
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (quote) VALUES (:quote)';
            $query2 = 'SELECT max(id) FROM ' . $this->table . ' WHERE id in (SELECT id FROM ' . $this->table . ' WHERE quote = :quote)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($query2);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt2->bindParam(':quote', $this->quote);

            // Execute query
            if($stmt->execute()) {
                if($stmt2->execute()) {
                    // Retrieve results of SELECT query to get id and quote
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

        // Update quote
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . ' SET quote = :quote WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
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

        // Delete quote
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