<?php
require_once (LIB_PATH.DS.'database.php');

class DatabaseObject
{
    public static function findAll()
    {
        return static::findBySql("SELECT * FROM " . static::$tableName);
    }

    public static function findById($id = 0)
    {
        global $database;
        $resultArray = static::findBySql("SELECT * FROM " . static::$tableName . " WHERE id=" . $database->escapeValue($id) . " LIMIT 1");
        return !empty($resultArray) ? array_shift($resultArray) : false;
    }

    public static function findBySql($sql = "")
    {
        global $database;
        $resultSet = $database->query($sql);
        $objectArray = array();
        while ($row = $database->fetchArray($resultSet))
        {
            $objectArray[] = static::instantiate($row);
        }
        return $objectArray;
    }

    public static function countAll()
    {
        global $database;
        $sql = "SELECT COUNT(*) FROM " . static::$tableName;
        $resultSet = $database->query($sql);
        $row = $database->fetchArray($resultSet);
        return array_shift($row);
    }

    private static function instantiate($record)
    {
        // Could check that $record exists and is an array
        // Simple, long-form approach:
        $object = new static;
        //$object->id = $record['id'];
        //$object->username = $record['username'];
        //$object->password = $record["password"];
        //$object->firstName = $record["first_name"];
        //$object->lastName = $record["last_name"];

        // More dynamic, short-form approach:
        foreach ($record as $attribute => $value)
        {
            if ($object->hasAttribute($attribute))
            {
                $object->$attribute = $value;
            }
        }

        return $object;
    }

    private function hasAttribute($attribute)
    {
        // get_object_vars returns an associative array with all atributes
        // (incl. private ones!) as the keys and their current values as the value
        $objectVars = get_object_vars($this);
        // We don't care about the value, we just want to know if the key exists
        // Will return true or false
        return array_key_exists($attribute, $objectVars);
    }

    protected function attributes()
    {
        // return an array of attributes keys and their values
        $attributes = array();
        foreach (static::$dbFields as $field)
        {
            if (property_exists($this, $field))
            {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    protected function sanitizedAttributes()
    {
        global $database;
        $cleanAttributes = array();
        // sanitize the values before submiting
        // Note: does not alter the actual value of each attribute
        foreach ($this->attributes() as $key => $value)
        {
            $cleanAttributes[$key] = $database->escapeValue($value);
        }
        return $cleanAttributes;
    }

    public function save()
    {
        // A new record won't have an id yet
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()
    {
        global $database;
        // Don't forget your sql syntax and good habits:
        // - INSERT INTO table (key,key) VALUES ('value', 'value')
        // - singel-quotes around values
        // - escape all values to prevent SQL injection
        $attributes = $this->sanitizedAttributes();
        $sql = "INSERT INTO " . static::$tableName . "("
            . join(", ", array_keys($attributes))
            . ") VALUES ('"
            . join("', '", array_values($attributes))
            . "')";
        if ($database->query($sql))
        {
            $this->id = $database->insertId();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function update()
    {
        global $database;
        // Don't forget your sql syntax and good habits:
        // - UPDATE table SET key='value', key='value' WHERE condition
        // - singel-quotes around values
        // - escape all values to prevent SQL injection
        $attributes = $this->sanitizedAttributes();
        $attributesPairs = array();
        foreach ($attributes as $key => $value)
        {
            $attributesPairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . static::$tableName . " SET "
            . join(", ", $attributesPairs)
            . " WHERE id=" . $database->escapeValue($this->id);
        $database->query($sql);
        return ($database->affectedRows() == 1) ? true : false;
    }

    public function delete()
    {
        global $database;
        // Don't forget your sql syntax and good habits:
        // - DELETE FROM table WHERE conditions LIMIT 1
        // - escape all values to prevent SQL injection
        // - use LIMIT 1
        $sql = "DELETE FROM " . static::$tableName
            . " WHERE id=" . $database->escapeValue($this->id)
            . " LIMIT 1";
        $database->query($sql);
        return ($database->affectedRows() == 1) ? true : false;
    }
}