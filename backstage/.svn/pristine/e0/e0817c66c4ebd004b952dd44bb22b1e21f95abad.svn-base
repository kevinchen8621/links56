<?php

/**
 * Created on 2013-9-12
 * Function: 数据库操作类
 * @author: hzq
 * @copyright: All Rights Reserved by hzq.
 * @version:1.0.0.0
 * @abstract mysql_connect函数说明：mysql_connect(server,user,pwd,newlink,clientflag);
 * new_link ：使 mysql_connect() 总是打开新的连接，甚至当 mysql_connect() 曾在前面被用同样的参数调用过。
 * client_flags 参数可以是以下常量的组合：
  MYSQL_CLIENT_SSL - 使用 SSL 加密
  MYSQL_CLIENT_COMPRESS - 使用压缩协议
  MYSQL_CLIENT_IGNORE_SPACE - 允许函数名后的间隔
  MYSQL_CLIENT_INTERACTIVE - 允许关闭连接之前的交互超时非活动时间
 * */
class mysql {

    private $version = '';
    private $querynum = 0;
    private $link = null;

    function __destruct() {
        $this->close();
    }

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE, $dbcharset2 = '') { // 链接数据库
        $func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect';
        if (!$this->link = @$func($dbhost, $dbuser, $dbpw, 1)) {
            $halt && $this->halt('Can not connect to MySQL server!');
        } else {
            mysql_query('SET NAMES UTF8');

            $dbname && @mysql_select_db($dbname, $this->link);
        }
    }

    function select_db($dbname) { // 选择数据库
        return mysql_select_db($dbname, $this->link);
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) { // 获取一条记录
        if (empty($query))
            return false;
        return mysql_fetch_array($query, $result_type);
    }

    function fetch_first($sql) { // 获取第一条记录
        return $this->fetch_array($this->query($sql));
    }

    function result_first($sql) { // 返回query的第一个字段
        return $this->result($this->query($sql), 0);
    }

    function query($sql, $type = '') {
        $func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
                'mysql_unbuffered_query' : 'mysql_query';

        if (!($query = $func($sql, $this->link))) {
            if (in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
                //$this->close();
                //require ROOT_PATH.'/config/config.php';
                //$this->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true, $dbcharset);
                //return $this->query($sql, 'RETRY'.$type);
            } elseif ($type != 'SILENT' && substr($type, 5) != 'SILENT') {

                $this->halt('MySQL Query Error', $sql);
            }
        }
        $this->querynum++;
        return $query;
    }

    function select($sql) { // 读取sql里面所有数据
        if ($query = $this->query($sql)) {
            $data = false;
            while ($rows = mysql_fetch_assoc($query)) {
                $data[] = $rows;
            }
            $this->free_result($query);
            return $data;
        }
        return false;
    }

    function affected_rows() {
        return mysql_affected_rows($this->link);
    }

    // task begin
    function begin() {
        $this->query('begin');
    }

    function rollback() {
        $this->query('rollback');
    }

    function commit() {
        $this->query('commit');
    }

    // task end
    function error() {
        return (($this->link) ? mysql_error($this->link) : mysql_error());
    }

    function errno() {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }

    function result($query, $row = 0) {
        $query = @mysql_result($query, $row);
        return $query;
    }

    function num_rows($query) { // 这次查询有多少条记录
        return mysql_num_rows($query);
    }

    function num_fields($query) { // 这次查询有多少个字段
        return mysql_num_fields($query);
    }

    function free_result($query) {
        return mysql_free_result($query);
    }

    function insert_id() {
        return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("select last_insert_id()"), 0);
    }

    function fetch_row($query) {
        return mysql_fetch_row($query);
    }

    function fetch_fields($query) {
        return mysql_fetch_field($query);
    }

    function list_fields($query) {
        $fields = array();
        $columns = mysql_num_fields($query);
        for ($i = 0; $i < $columns; $i++) {
            $fields[] = mysql_field_name($query, $i);
        }
        return $fields;
    }

    function version() {
        if (empty($this->version)) {
            $this->version = mysql_get_server_info($this->link);
        }
        return $this->version;
    }

    function close() {
        return mysql_close($this->link);
    }

    function halt($message = '', $sql = '') {
        $timestamp = time();
        if ($message) {
            $errmsg = "<b>System info</b>: $message<br/>";
        }
        $errmsg .= "<b>Time</b>: " . gmdate("Y-n-j g:ia", $timestamp) . "<br/>";
        $errmsg .= "<b>Script</b>: " . $_SERVER['PHP_SELF'] . "<br/>";
        if ($sql) {
            $errmsg .= "<b>SQL</b>: " . htmlspecialchars($sql) . "<br/>";
        }
        $errmsg .= "<b>Error</b>:  " . $this->error() . "<br/>";
        $errmsg .= "<b>Errno.</b>:  " . $this->errno();
        echo $errmsg;
        exit;
    }

    function fn_update($table, $param, $condition) {  //update more than one field.
        foreach ($param as $key => $value) {
            $this->query("update $table set $key = $value where $condition");
        }
    }

    // 数据库-增操作
    function insert($table, $data) {
        foreach ((array) $data as $key => $value) {

            $data[$key] = $value;
        }
        $fields = implode(',', array_keys($data));
        $values = "'" . implode("','", array_values($data)) . "'";
        $sql = "insert into $table($fields) values ($values)";
        return $this->query($sql);
    }

    function fn_insert($table, $name, $value) {
        $this->query("insert into $table ($name) values ($value)");
    }

    function fn_delete($table, $condition) {
        $this->query("delete from $table where $condition");
    }

}