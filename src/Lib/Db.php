<?php

namespace Lib;
use Lib\Config;

class Db
{
    private $db;

    public function __construct()
    {
        $dbDriver = Config::get('database.driver');
        $dbHost = Config::get('database.host');
        $dbName = Config::get('database.name');
        $dbUser = Config::get('database.user');
        $dbPassword = Config::get('database.password');

        if ($dbDriver == 'sqlite') {
            $this->db = new \PDO('sqlite:' . __DIR__ . '/../../' . $dbName);
        } else {
            $this->db = new \PDO($dbDriver . ':host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
        }

        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }

    /**
     * Database migration up
     */
    public function migrateUp($table, $fields)
    {
        $sql = 'CREATE TABLE ' . $table . ' (';

        foreach ($fields as $fieldName => $def) {
            $sql .= $fieldName . ' ' . $def . ', ';
        }

        $sql = rtrim($sql, ', ') . ')';

        return $this->execute($sql);
    }

    /**
     * Database migration down
     */
    public function migrateDown($table)
    {
        $sql = 'DROP TABLE ' . $table;

        $this->execute($sql);
    }

    /**
     * Perform inserting data
     */
    public function insert($table, $data)
    {
        $fieldNames = [];
        $values = [];

        foreach ($data as $key => $value)
        {
            if (!is_null($value) && $value != '0000-00-00 00:00:00') {
                if (is_string($value))
                    $value = $this->db->quote($value);

                $fieldNames[] = $key;
                $values[] = $value;
            }
        }

        $fieldNames = implode(', ', $fieldNames);
        $values = implode(', ', $values);

        $sql = "INSERT INTO $table ($fieldNames) VALUES ($values)";

        $this->execute($sql);
    }

    /**
     * Perform updating data
     */
    public function update($table, $id, $data)
    {
        $sql = "UPDATE $table SET ";

        foreach ($data as $fieldName => $value)
        {
            $sql .= $fieldName . ' = ' . (is_string($value) ? $this->db->quote($value) : $value) . ', ';
        }

        $sql = rtrim($sql, ', ');
        $sql .= ' WHERE id = ' . $id;

        return $this->execute($sql);
    }

    /**
     * Execute sql statement
     */
    private function execute($sql)
    {
        try {
            $execReturn = $this->db->exec($sql);
            $this->db = null;

            return $execReturn;
        } catch (Exception $e) {
            $this->db = null;
            return $e->getMessage();
        }
    }
}
