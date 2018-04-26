<?php
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase
{

    private $connection;

    function __construct()
    {
        $this->openConnection();
    }

    public function openConnection()
    {
        $this->connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
        if(mysqli_connect_errno())
        {
            die("Database connection failed: ".mysqli_connect_error()."(".mysqli_connect_erno().")");
        }
    }

    public function closeConnection()
    {
        if(isset($this->connection))
        {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->connection, $sql);
        $this->confirmQuery($result);
        return $result;
    }

    private function confirmQuery($result)
    {
        if(!$result)
        {
            die("Database query failed.");
        }
    }

    public function escapeValue($string)
    {
        $escapedString = mysqli_real_escape_string($this->connection, $string);
        return $escapedString;
    }


    // "database neutral" functions
    public function fetchArray($resultSet)
    {
        return mysqli_fetch_array($resultSet);
    }

    public function numRows($resultSet)
    {
        return mysqli_num_rows($resultSet);
    }

    public function insertId()
    {
        // get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }

    public function affectedRows()
    {
        return mysqli_affected_rows($this->connection);
    }
}

$database = new MySQLDatabase();