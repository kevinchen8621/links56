<?php

class session_operator_native {

    function __construct() {
        
    }

    /**
     * 注册一个session存储器实例。
     * 对于本原生操作器来讲，这个为可选方法，并且要在本类的session_start之前启动。
     * @param object &$handler session存储器实例
     */
    function setStorageHandler(&$handler) {
        session_set_save_handler(array(&$handler, "open"), array(&$handler, "close"), array(&$handler, "read"), array(&$handler, "write"), array(&$handler, "destroy"), array(&$handler, "gc"));
    }

    function clear() { // 清除session
        $name = $GLOBALS['env']['name'];
        $_SESSION [$name] = array();
    }

    function set($k, $v = false) { // 设置session
        $name = $GLOBALS['env']['name'];
        if (is_array($k)) {
            $_SESSION[$name] = array_merge($name, $k);
        } else if (!empty($k)) {
            $_SESSION[$name][$k] = $v;
        }
    }

    function get($k = null) { // 获取session
        $name = $GLOBALS['env']['name'];
        if (empty($k)) {
            return $_SESSION[$name];
        } else {
            return isset($_SESSION[$name][$k]) ? $_SESSION[$name][$k] : null;
        }
    }

    function del($k) { // 删除某个session
        $name = $GLOBALS['env']['name'];
        if (!empty($_SESSION [$name])) {
            if (!is_array($k)) {
                $k = array($k);
            }
            foreach ($k as $kv) {
                if (isset($_SESSION[$name][$kv]))
                    unset($_SESSION[$name][$kv]);
            }
            return;
        }
    }

    function session_id($id = null) { // 模拟php函数session_id
        return session_id($id);
    }

    function session_regenerate_id($delete_old_session = false) { // 模拟php函数session_regenerate_id，重新生成并设置一个session_id
        return session_regenerate_id($delete_old_session);
    }

    function session_start() { // 模拟php函数session_start，启动session机制。
        session_start();
        $name = $GLOBALS['env']['name'];
        if (isset($_SESSION[$name]) && !is_array($_SESSION[$name])) {
            $this->clear();
        }
    }

}

?>