<?php

class PDOEx extends PDO {
    private $queryCount = 0;
    private $queryArray=array();

    public function query($query) {
    // Increment the counter.
        $this->queryCount++;
        $this->queryArray[]=$query;

    // Run the query.
        return parent::query($query);
    }

    public function exec($statement) {
    // Increment the counter.
        $this->queryCount++;
        $this->queryArray[]=$statement;

    // Execute the statement.
        return parent::exec($statement);
    }

    public function prepare($query) {
    // Increment the counter.
        $this->queryCount++;
        $this->queryArray[]=$query;

    // Execute the statement.
        return parent::prepare($query);
    }

    public function GetCount() {
        return $this->queryCount;
    }

    public function GetQueries() {
        return $this->queryArray;
    }
}

?>