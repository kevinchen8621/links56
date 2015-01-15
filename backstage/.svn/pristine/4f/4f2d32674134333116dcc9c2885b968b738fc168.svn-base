<?php

class ccommon {

    public $env = null; // 基础环境的所有参数
    public $memcache = null; // memcache缓冲
    private $db = null; // 数据库指针
    public $session = null; // session

    function __construct() {
        global $env;
        $this->env = &$env; // 直接引用
    }

    function initMemcache() { // 初始化memcache， 每个子类使用前需要初始化
        if (empty($this->memcache)) {
            if (empty($this->env['memcache'])) {
                $this->env['memcache'] = new Memcache();
            }
            $this->memcache = $this->env['memcache'];
            if ($this->memcache->connect("127.0.0.1")) { // ("127.0.0.1");
            } else {
                $parm['title'] = '初始化缓存失败';
                $parm['msg'] = '初始化缓存失败';
                $this->showErr($parm);
            }
        }
    }

    function initSession() { // 初始化session， 每个子类使用前需要调用
        if (empty($this->session)) {
            if (empty($this->env['session'])) {
                $this->env['session'] = new Memcache();
                require_once(CORE_PATH . '/session/session_operator_native.class.php');
                $this->env['session'] = new session_operator_native();
                $this->env['session']->session_start();
            }
            $this->session = $this->env['session'];
        }
    }

    function loadModule() { // 加载数据模型
        if (empty($this->db)) { // 第一次调用的时候才加载
            if (!empty($this->env['db'])) {
                $this->db = $this->env['db'];
            } else {
                require_once(CORE_PATH . '/mysql.class.php');
                require_once(CORE_PATH . '/cmodule.class.php'); // 基类
                $this->env['db'] = $this->db = new mysql();
                $env = &$this->env;
                $this->db->connect($env['dbhost'], $env['dbuser'], $env['dbpw'], $env['dbname']);
            }
        }

        $num = func_num_args();
        $list = func_get_args();
        if ($num == 0) {
            $this->cmodule = new cmodule($this->db);
        } else {
            for ($i = 0; $i < $num; $i++) {
                $modulename = $list[$i];
                if (empty($this->$modulename)) { // 已经加载过就不需要再加载了
                    $path = $this->departPath($modulename);
                    $file = MODULE_PATH . "/{$path}{$modulename}.class.php";
                    if (!include_once($file)) {
                        $parm['title'] = '文件不存在';
                        $parm['msg'] = $file;
                        $this->showErr($parm);
                    }
                    $this->$modulename = new $modulename($this->db);
                }
            }
        }
        return true;
    }

    function getGets($key, $default = null) { // 获取网址GET的参数
        return (isset($_GET[$key])) ? $_GET[$key] : $default;
    }

    function getPosts($key, $default = null) { // 获取网址GET的参数
        return (isset($_POST[$key])) ? $_POST[$key] : $default;
    }

    function getServers($key, $default = null) { // 获取网址SERVER的参数
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

    function getFiles($file = 'upfile') { // 获取上传的文件内容
        // name, type, tmp_name, size, error=0
        if (isset($_FILES[$file])) {
            if ($_FILES[$file]['error'] == 0) {
                return $_FILES[$file];
            } else {
                return $_FILES[$file]['error'];
            }
        }
//        array(
//            'name' => 'back.png',
//            'type' => 'image/png',
//            'tmp_name' => 'C:\\Windows\\Temp\\phpE9BE.tmp',
//            'error' => 0,
//            'size' => 1645,
//        );
        return -1;
    }

    function savefile($data, $file, $mode) { // 记录到data目录下
        $path = $this->departPath($file);
        $logfile = DATA_PATH . '/' . $path . $file;
        $f = fopen($logfile, $mode);
        fwrite($f, $data);
        fclose($f);
    }

    function loadfile($file, $len = 0) { // 从data目录下去读
        $path = $this->departPath($file);
        $logfile = DATA_PATH . '/' . $path . $file;
        if (!file_exists($logfile)) {
            return null;
        }
        $f = fopen($logfile, 'r');
        $data = '';
        if (empty($len)) {
            while (!feof($f)) {
                $data .= fread($f, 1024);
            }
        } else {
            $data = fread($f, $len);
        }
        fclose($f);
        return $data;
    }

}
