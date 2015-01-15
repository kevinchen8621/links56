<?php

class cmodule extends ccommon {

    public $db = '';
    public $table = '';

    function __construct($db, $table = '') {
        $this->db = $db;
        $this->table = $table;
    }

    function filter($keys, $parm) { // 过滤有效字段
        $data = array();
        foreach ($keys as $k) {
            if (isset($parm[$k])) {
                $data[$k] = $parm[$k];
            }
        }
        return $data;
    }

    function getCount($where = 1, $table = '') {
        if (empty($this->db)) {
            return 0;
        }
        $table = empty($table) ? $this->table : $table;
        $sql = 'SELECT count(*) as count FROM ' . $table . ' WHERE ' . $where . ' LIMIT 1';
        $data = $this->db->fetch_first($sql);
        return empty($data) ? 0 : $data['count'];
    }

    function getList($where = '', $start = 0, $len = 10, $table = '') {
        if (empty($this->db)) {
            return 0;
        }
        $table = empty($table) ? $this->table : $table;
        $sql = 'SELECT * FROM `' . $table . '` WHERE ' . $where . ' LIMIT ' . $start . ', ' . $len;
        //   return $sql;
        return $this->db->select($sql);
    }

    function getListall($where = '', $table = ''){
        if (empty($this->db)) {
            return 0;
        }
        $table = empty($table) ? $this->table : $table;
        $sql = 'SELECT * FROM `' . $table . '` WHERE ' . $where ;
        //   return $sql;
        return $this->db->select($sql);
    }

    public function insert($data, $table = '') {
        if (empty($this->db)) {
            return 0;
        }
        $i = 0;
        $table = empty($table) ? $this->table : $table;
        $key = $val = '';
        foreach ($data as $k => $v) {
            $a = ($i > 0) ? ',' : '';
            $key .=$a . '`' . $k . '`';
            $val .= $a . "'" . addslashes($v) . "'";
            $i++;
        }
        $sql = 'INSERT INTO `' . $table . '` (' . $key . ') VALUES (' . $val . ')';
        $this->db->query($sql);
        return $this->db->insert_id(); // 直接返回id
    }

    public function update($data, $where = 1, $table = '') {
        if (empty($this->db)) {
            return 0;
        }
        $i = 0;
        $table = empty($table) ? $this->table : $table;
        $val = '';
        foreach ($data as $k => $v) {
            $a = ($i > 0) ? ',' : '';
            $val .= $a . $k . "='" . addslashes($v) . "'";
            $i++;
        }

        $sql = 'UPDATE `' . $table . '` SET ' . $val . ' WHERE ' . $where;
        $this->db->query($sql);
        $rc = $this->db->affected_rows();
        return ($rc === false) ? 0 : 1;
    }

    public function delete($where = 1, $table = '') {
        if (empty($this->db)) {
            return 0;
        }
        $table = empty($table) ? $this->table : $table;
        $sql = 'DELETE FROM `' . $table . '` WHERE ' . $where;
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    public function query($sql) { // 直接执行sql语句
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    // task begin
    function begin() {
        if (empty($this->db)) {
            return 0;
        }
        return $this->db->query('begin');
    }

    function rollback() {
        if (empty($this->db)) {
            return 0;
        }
        return $this->db->query('rollback');
    }

    function commit() {
        if (empty($this->db)) {
            return 0;
        }
        return $this->db->query('commit');
    }

    // task end
    function lock($islockwirte = true, $table = null) {
        if (empty($this->db)) {
            return 0;
        }
        $readwrite = $islockwirte ? 'WRITE' : 'READ';
        $table = empty($table) ? $this->table : $table;
        return $this->db->query('LOCK TABLES ' . $table . ' ' . $readwrite);
    }

    function unlock() {
        if (empty($this->db)) {
            return 0;
        }
        return $this->db->query('UNLOCK TABLES');
        // $sql = 'lock tables ppc2code write,a201312santa write';
    }

}
