<?php

class Word {

    private $id;
    private $name;
    private $explanation;
    private $type;
    private $use;
    private $difficulty;
    private $status;
    private $userId;
    private $synonyms;
    private $related;

    function __construct($id, $name, $explanation, $type, $use, $difficulty, $status, $userId, $synonyms, $related) {
        $this->id = $id;
        $this->name = $name;
        $this->explanation = $explanation;
        $this->type = $type;
        $this->use = $use;
        $this->difficulty = $difficulty;
        $this->status = $status;
        $this->userId = $userId;
        $this->synonyms = $synonyms;
        $this->related = $related;
    }

    static function fromId($id) {
        $id = DatabaseManager::escape($id);
        $query = "SELECT * FROM words WHERE id = $id";
        $result = DatabaseManager::query($query);
        if ($row = DatabaseManager::getRow($result)) {
            return Word::fromRow($row);
        } return NULL;
    }

    static function fromName($name) {
        $name = DatabaseManager::escape($name);
        $query = "SELECT * FROM words WHERE name = '$name'";
        $result = DatabaseManager::query($query);
        if ($row = DatabaseManager::getRow($result)) {
            return Word::fromRow($row);
        } return NULL;
    }

    static function fromRow($row) {
        return new Word($row['id'], $row['name'], $row['explanation'], $row['type'], $row['use'], $row['difficulty'], $row['status'], $row['userId'], $row['synonyms'], $row['related']);
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return trim($this->name);
    }

    function getExplanation() {
        return $this->explanation;
    }
    
    function getDefinition() {
        return explode(PHP_EOL, $this->explanation)[0];
    }

    function getType() {
        return $this->type;
    }

    function getUse() {
        return $this->use;
    }

    function getDifficulty() {
        return $this->difficulty;
    }

    function getStatus() {
        return $this->status;
    }

    function getUserId() {
        return $this->userId;
    }

    function getSynonyms() {
        return $this->synonyms;
    }

    function getRelated() {
        return $this->related;
    }
    
    public static function add($word) {
        $query = "INSERT INTO words (name, explanation, type, `use`, difficulty, synonyms, related, status, userId) VALUES "
                . "('{$word->getName()}', "
                . "'{$word->getExplanation()}', "
                . "'{$word->getType()}', "
                . "'{$word->getUse()}', "
                . "'{$word->getDifficulty()}', "
                . "'{$word->getSynonyms()}', "
                . "'{$word->getRelated()}', "
                . "'{$word->getStatus()}', "
                . "'{$word->getUserId()}')";
        DatabaseManager::query($query);
    }
    
    public static function getWords($data)
    {
        $query = "SELECT * FROM words WHERE status = 'accepted'";
        if (isset($_GET['name'])) {
            $name = DatabaseManager::escape($_GET['name']);
            $query .= " AND name LIKE '%$name%'";
        }
        if (isset($_GET['saved']) && isset($data['user'])) {
            $saved = $_GET['saved'];
            $user = $data['user'];
            if ($saved == "true") {
                $query .= " AND id IN (SELECT wordId FROM user_words WHERE userId = '{$user->getId()}')";
            } else if ($saved == "false") {
                $query .= " AND id NOT IN (SELECT wordId FROM user_words WHERE userId = '{$user->getId()}')";
            }
        }
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            if ($type != "all") {
                $query .= " AND type = '$type'";
            }
        }
        
        $words = array();
        $wordsQueryResult = DatabaseManager::query($query);
        while ($row = DatabaseManager::getRow($wordsQueryResult)) {
            array_push($words, Word::fromRow($row));
        }
        return $words;
    }

    public static function getSelectedWord()
    {
        if (isset($_GET['id'])) {
            return Word::fromId($_GET['id']);
        } else {
            return NULL;
        }
    }
    
    public static function isWordCreated($name) {
        $name = DatabaseManager::escape($name);
        $query = "SELECT * FROM words WHERE name = '$name'";
        DatabaseManager::query($query);
        if (DatabaseManager::resultCount() != 0) {
            return true;
        } return false;
    }

}
