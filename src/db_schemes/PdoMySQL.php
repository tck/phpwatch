<?php

require_once PW2_PATH . '/src/QueryBuilder.php';

class PdoMySQL extends QueryBuilder
{
    protected $quoteIdentifierSymbol = '`';

    public function connect()
    {
        try {
            $this->link = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pw, array(
                PDO::ATTR_PERSISTENT => false,
            ));
        } catch (Exception $e) {
            return false;
        }
        
        // Set Connection Charset
        $this->query("SET NAMES 'latin1'");
        $this->query("SET CHARACTER SET 'latin1'");
        
        return true;
    }

    protected function fetchAssoc($result)
    {
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function query($sql, $values = array())
    {
        $sth = $this->link->prepare($sql);
        $sth->execute($values);
        
        return $sth;
    }
    
    public function quote($string)
    {
        return $this->link->quote($string);
    }

    public function numRecords($result)
    {
        return $result->rowCount();
    }
    
    public function lastInsertId()
    {
        return $this->link->lastInsertId();
    }
}
