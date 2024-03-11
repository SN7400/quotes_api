<?php
    class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // Category Properties
        public $id;
        public $category;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get categories
        public function read() {
            // Create query
            $query = 'SELECT 
                a.id,
                a.category
                FROM
                ' . $this->table . ' a';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get single category
        public function read_single() {
            // Create query
            $query = 'SELECT 
                a.id,
                a.category
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
                $this->category = $row['category'];
            }
        }

        // Create category
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
            $query2 = 'SELECT max(id) FROM ' . $this->table . ' WHERE id in (SELECT id FROM ' . $this->table . ' WHERE category = :category)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($query2);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt2->bindParam(':category', $this->category);

            // Execute query
            if($stmt->execute()) {
                if($stmt2->execute()) {
                    // Retrieve results of SELECT query to get id and category
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

        // Update category
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
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

        // Delete category
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