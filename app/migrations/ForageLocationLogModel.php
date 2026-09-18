<?php

class ForageLocationLogModel_Migration {
    private $database = null;
    private $connection = null;

    public function __construct($pdo)
    {
        $this->connection = $pdo;
    }

    public function up()
    {
        $this->database = new Asatru\Database\Migration('ForageLocationLogModel', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('location_id INT NOT NULL');
        $this->database->add('entry_date DATE NOT NULL');
        $this->database->add('note TEXT NULL');
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
