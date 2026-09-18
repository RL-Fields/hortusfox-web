<?php

class ForageCategoryPhotoModel_Migration {
    private $database = null;
    private $connection = null;

    public function __construct($pdo)
    {
        $this->connection = $pdo;
    }

    public function up()
    {
        $this->database = new Asatru\Database\Migration('ForageCategoryPhotoModel', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('category INT NOT NULL');
        $this->database->add('author INT NOT NULL DEFAULT 0');
        $this->database->add('thumb VARCHAR(255) NOT NULL');
        $this->database->add('original VARCHAR(255) NOT NULL');
        $this->database->add('label VARCHAR(255) NULL');
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
