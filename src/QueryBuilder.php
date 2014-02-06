<?php

abstract class QueryBuilder
{
    protected $host;
    protected $db;
    protected $user;
    protected $pw;
    
    protected $link;

    protected $quoteIdentifierSymbol = '';

    public function __construct($host, $db, $user, $pw)
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pw = $pw;
    }

    protected abstract function connect();

    protected abstract function query($sql, $values = array()); // CHANGE tck
    protected abstract function fetchAssoc($result); // NEW tck
    public abstract function quote($string); // NEW tck
    //public abstract function executeSelect($fields, $table, $suffix);
    //public abstract function executeSelectOne($fields, $table, $suffix);
    //public abstract function executeInsert($fields, $table);
    //public abstract function executeUpdate($fields, $table, $suffix);
    //public abstract function executeDelete($table, $suffix);
    //public abstract function executeRaw($sql);
    public abstract function numRecords($result);
    public abstract function lastInsertId();


    // NEW tck
    public function quoteIdent($string)
    {
        $qis = $this->quoteIdentifierSymbol;
        return $qis . str_replace($qis, $qis . $qis, $string) . $qis;
    }
        
    public function executeSelectOne($fields, $table, $suffix)
    {
        return $this->fetchAssoc($this->query('SELECT ' . $fields . ' FROM `' . $table . '` ' . $suffix));
    }

    public function executeSelect($fields, $table, $suffix)
    {
        $rows = array();
        $result = $this->query('SELECT ' . $fields . ' FROM `' . $table . '` ' . $suffix);
        while ($row = $this->fetchAssoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function executeInsert($fields, $table)
    {
        $this->query('INSERT INTO `' . $table . '` (`' . implode('`,`', array_keys($fields)) . '`)'
            . ' VALUES (' . implode(',', array_fill(0, count($fields), '?')) . ')', array_values($fields));
        
        return $this->lastInsertId();
    }

    public function executeUpdate($fields, $table, $suffix)
    {
        $updates = array();
        foreach ($fields as $k => $v) {
            $updates[] = '`' . $k . '` = ?';
        }
        return $this->query('UPDATE `' . $table . '` SET ' . implode(',', $updates) . ' ' . $suffix, array_values($fields));
    }

    public function executeDelete($table, $suffix)
    {
        return $this->query('DELETE FROM `' . $table . '` ' . $suffix);
    }

    public function executeRaw($query, $values = array())
    {
        $result = $this->query($query, $values);
        $rows = array();
        while ($row = $this->fetchAssoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
