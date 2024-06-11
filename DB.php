<?php

// Interfejs za konekciju
interface DBConnectionInterface {
    public function connect();
    public function close();
}

// Klasa za MySQL konekciju koja implementira interfejs
class MySQLConnection implements DBConnectionInterface {
    private $connection;
    private $db_host;
    private $db_username;
    private $db_password;
    private $db_database;

    public function __construct($db_host, $db_username, $db_password, $db_database) {
        $this->db_host = $db_host;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_database = $db_database;
    }

    public function connect() {
        $this->connection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_database);
        if ($this->connection->connect_error) {
            die("Connection error: " . $this->connection->connect_error);
        }
        return $this->connection;
    }

    public function close() {
        $this->connection->close();
    }
}

// Interfejs za izvršavanje upita
interface DBQueryInterface {
    public function executeQuery($query);
    public function fetchSingleRow($result);
    public function fetchAllRows($result);
    public function escapeString($string);
}

// Klasa za rad sa bazom podataka koja implementira interfejs
class DBHandler implements DBQueryInterface {
    private $connection;

    public function __construct(DBConnectionInterface $connection) {
        $this->connection = $connection->connect();
    }

    public function executeQuery($query) {
        $result = $this->connection->query($query);
        if (!$result) {
            die("SQL query execution error: " . $this->connection->error);
        }
        return $result;
    }

    public function fetchSingleRow($result) {
        return $result->fetch_assoc();
    }

    public function fetchAllRows($result) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }

    public function __destruct() {
        $this->connection->close();
    }
}
