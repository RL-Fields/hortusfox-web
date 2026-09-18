<?php

class ForageLocationModel_Migration {
    private $database = null;
    private $connection = null;

    public function __construct($pdo)
    {
        $this->connection = $pdo;
    }

    public function up()
    {
        $this->database = new Asatru\Database\Migration('ForageLocationModel', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('category_id INT NOT NULL');
        $this->database->add('latitude DECIMAL(10,7) NOT NULL');
        $this->database->add('longitude DECIMAL(10,7) NOT NULL');
        $this->database->add('notes TEXT NULL');
        $this->database->add('last_visited DATE NULL');
        $this->database->add('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->database->add('updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->database->create();
    }

    public function down()
    {
        if ($this->database)
            $this->database->drop();
    }
}
