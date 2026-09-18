<?php

class ForageCategoryModel_Migration {
    private $database = null;
    private $connection = null;

    public function __construct($pdo)
    {
        $this->connection = $pdo;
    }

    public function up()
    {
        $this->database = new Asatru\Database\Migration('ForageCategoryModel', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('name VARCHAR(512) NOT NULL');
        $this->database->add('season_start_month TINYINT NULL');
        $this->database->add('season_end_month TINYINT NULL');
        $this->database->add('notes TEXT NULL');
        $this->database->add('grocy_name VARCHAR(512) NULL');
        $this->database->add('harvested TINYINT(1) NOT NULL DEFAULT 0');
        $this->database->add('photo VARCHAR(255) NULL');
        $this->database->add('type VARCHAR(30) NULL');
        $this->database->add('habitat VARCHAR(30) NULL');
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
