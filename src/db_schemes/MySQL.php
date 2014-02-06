<?php

require_once PW2_PATH . '/src/QueryBuilder.php';

class MySQL extends QueryBuilder
{
    protected $quoteIdentifierSymbol = '`';

    public function connect()
    {
        $this->link = @mysql_connect($this->host, $this->user, $this->pw);
        if (!$this->link) {
            return false;
        }
        mysql_select_db($this->db);

        // Set Connection Charset
        $this->query("SET NAMES 'latin1'");
        $this->query("SET CHARACTER SET 'latin1'");

        return true;
    }

    public function quote($string)
    {
        return "'" . mysql_real_escape_string($string, $this->link) . "'";
    }

//        private function sanitize($value)
//        {
//            return mysql_real_escape_string($value);
//        }

    protected function fetchAssoc($result)
    {
        return mysql_fetch_assoc($result);
    }

    public function query($sql, $values = array())
    {
        $mergedSql = strtok($sql, '?');
        $tok = strtok('?');
        $i = 0;
        while ($tok !== false) {
            $mergedSql .= (isset($values[$i]) ? $this->quote($values[$i]) : '?') . $tok;
            $tok = strtok('?');
            $i++;
        }
        return mysql_query($mergedSql, $this->link);
    }

//        public function executeSelectOne($fields, $table, $suffix)
//        {
//            return $this->fetchAssoc($this->query('SELECT ' . $fields . ' FROM `' . $table . '` ' . $suffix));
//        }
//
//        public function executeSelect($fields, $table, $suffix)
//        {
//            $rows = array();
//            $result = $this->query('SELECT ' . $fields . ' FROM `' . $table . '` ' . $suffix);
//            while($row = $this->fetchAssoc($result))
//                $rows[] = $row;
//            return $rows;
//        }
//
//        public function executeInsert($fields, $table)
//        {
//            foreach($fields as $k => $v)
//                $fields[$k] = MySQL::sanitize($v);
//            $this->query('INSERT INTO `' . $table . '` (`' . implode('`,`', array_keys($fields)) . '`) VALUES (\'' .
//                implode('\',\'', array_values($fields)) . '\')');
//            return mysql_insert_id($this->link);
//        }
//
//        public function executeUpdate($fields, $table, $suffix)
//        {
//            $updates = array();
//            foreach($fields as $k => $v)
//                $updates[] = $k . '=\'' . MySQL::sanitize($v) . '\'';
//            return $this->query('UPDATE `' . $table . '` SET ' . implode(',', $updates) . ' ' . $suffix);
//        }
//
//        public function executeDelete($table, $suffix)
//        {
//            return $this->query('DELETE FROM `' . $table . '` ' . $suffix);
//        }
//
//        public function executeRaw($query)
//        {
//            $result = $this->query($query);
//            $rows = array(); // NEW tck
//            while($row = $this->fetchAssoc($result))
//                $rows[] = $row;
//            return $rows;
//        }

    public function numRecords($result)
    {
        return mysql_num_rows($result);
    }
    
    public function lastInsertId()
    {
        return mysql_insert_id($this->link);
    }
}
