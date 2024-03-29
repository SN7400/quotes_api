<?php
    class Quote {
        // DB stuff
        private $conn;
        private $qtable = 'quotes';
        private $atable = 'authors';
        private $ctable = 'categories';

        // Quote Properties
        public $id;
        public $quote;
        public $author;
        public $author_id;
        public $category;
        public $category_id;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get quotes
        public function read() {
            // Check if author_id exists
            if($this->author_id) {
                $check_query = 'SELECT EXISTS(SELECT 1 FROM authors WHERE id = :author_id LIMIT 1)';
                $check_stmt = $this->conn->prepare($check_query);
                $check_stmt->bindParam(':author_id', $this->author_id);
                $check_stmt->execute();
                $check_result = $check_stmt->fetch(PDO::FETCH_ASSOC);
                if(!$check_result['exists']) {
                    return 'author_id Not Found';
                }
            }

            //Check if category_id exists
            if($this->category_id) {
                $check_query = 'SELECT EXISTS(SELECT 1 FROM categories WHERE id = :category_id LIMIT 1)';
                $check_stmt = $this->conn->prepare($check_query);
                $check_stmt->bindParam(':category_id', $this->category_id);
                $check_stmt->execute();
                $check_result = $check_stmt->fetch(PDO::FETCH_ASSOC);
                if(!$check_result['exists']) {
                    return 'category_id Not Found';
                }
            }

            // Create query
            if($this->author_id && $this->category_id) {
                $query = 'SELECT 
                q.id,
                q.quote,
                a.author,
                c.category
                FROM
                ' . $this->qtable . ' q
                INNER JOIN ' . $this->atable . ' a ON q.author_id = a.id
                INNER JOIN ' . $this->ctable . ' c ON q.category_id = c.id
                WHERE
                    q.author_id = :author_id AND q.category_id = :category_id';
            } elseif ($this->author_id) {
                $query = 'SELECT 
                q.id,
                q.quote,
                a.author,
                c.category
                FROM
                ' . $this->qtable . ' q
                INNER JOIN ' . $this->atable . ' a ON q.author_id = a.id
                INNER JOIN ' . $this->ctable . ' c ON q.category_id = c.id
                WHERE
                    q.author_id = :author_id';
            } elseif ($this->category_id) {
                $query = 'SELECT 
                q.id,
                q.quote,
                a.author,
                c.category
                FROM
                ' . $this->qtable . ' q
                INNER JOIN ' . $this->atable . ' a ON q.author_id = a.id
                INNER JOIN ' . $this->ctable . ' c ON q.category_id = c.id
                WHERE
                    q.category_id = :category_id';
            } else {
                $query = 'SELECT 
                q.id,
                q.quote,
                a.author,
                c.category
                FROM
                ' . $this->qtable . ' q
                INNER JOIN ' . $this->atable . ' a ON q.author_id = a.id
                INNER JOIN ' . $this->ctable . ' c ON q.category_id = c.id';
            }

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind parameters if there are any
            if ($this->author_id) {
                $stmt->bindParam(':author_id', $this->author_id);
            }

            if ($this->category_id) {
                $stmt->bindParam(':category_id', $this->category_id);
            }

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get single quote
        public function read_single() {
            // Create query
            $query = 'SELECT 
                q.id,
                q.quote,
                a.author,
                c.category
                FROM
                ' . $this->qtable . ' q
                INNER JOIN ' . $this->atable . ' a ON q.author_id = a.id
                INNER JOIN ' . $this->ctable . ' c ON q.category_id = c.id
                WHERE
                    q.id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(':id', $this->id);

            //Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            if($row) {
                //$this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
                return true;
            } else {
                return false;
            }
        }

        // Create quote
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->qtable . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
            $query2 = 'SELECT max(id) FROM ' . $this->qtable . ' WHERE id in (SELECT id FROM ' . $this->qtable . ' WHERE quote = :quote)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($query2);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
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
            $query = 'UPDATE ' . $this->qtable . '
            SET (quote, author_id, category_id) = (:quote, :author_id, :category_id)
            WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

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
            $query = 'DELETE FROM ' . $this->qtable . ' WHERE id = :id';

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