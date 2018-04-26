<?php
require_once (LIB_PATH.DS.'database.php');

class User extends DatabaseObject
{
    protected static $tableName = "users";
    protected static $dbFields = array('id', 'username', 'password', 'first_name', 'last_name', 'super_admin');

    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $super_admin;

    public function fullName()
    {
        if (isset($this->first_name) && isset($this->last_name))
        {
            return $this->first_name . " " . $this->last_name;
        }
        else
        {
            return "";
        }
    }

    public static function authenticate($username = "", $password = "")
    {
        global $database;
        $username = $database->escapeValue($username);
        $password = $database->escapeValue($password);

        $sql = "SELECT * FROM users ";
        $sql .= "WHERE username = '{$username}' ";
        $sql .= "LIMIT 1";
        $resultArray = self::findBySql($sql);

        return !empty($resultArray) && password_verify($password, $resultArray[0]->password) ? array_shift($resultArray) : false;
    }

}