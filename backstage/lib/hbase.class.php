<?php

if (!defined('YSC')) {
    exit('Access Denied');
}

class hbase {

    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    function runWXLib($wlib, $act, $parm = array()) { // 直接运行某个微信库里面的函数
        if (!isset($GLOBALS['wxlib_' . $wlib])) {
            if (!include_once(WXLIB_PATH . '/' . $wlib . '.class.php'))
                return false;
            $GLOBALS['wxlib_' . $wlib] = new $wlib();
        }
        return $GLOBALS['wxlib_' . $wlib]->$act($parm);
    }

    function loadDB() {
        $num = func_num_args();
        $list = func_get_args();
        for ($i = 0; $i < $num; $i++) {
            $dbname = $list[$i];
            if (!include_once(DB_PATH . "/{$dbname}.db.php"))
                return false;
            @$this->$dbname = new $dbname($this->db);
        }
        return true;
    }

    function runDB($dbname, $act, $parm = array()) { // 直接运行某个db里面的函数
        if (!isset($GLOBALS['db_' . $dbname])) {
            $path = departPath($dbname);
            if (!include_once(DB_PATH . '/' . $path . $dbname . '.db.php'))
                return false;
            $GLOBALS['db_' . $dbname] = new $dbname($GLOBALS['db']);
        }

        return $GLOBALS['db_' . $dbname]->$act($parm);
    }

}
