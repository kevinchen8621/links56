<?php

if (!defined('YSTYLE')) {
    exit('Access Denied');
}

class cbase extends ccommon {

    public $log = null; // 记录log

    function __construct() {
        parent::__construct();
    }

    function loadHtml($htmlfile, $data = array(), $name = '') { // 打开html网页
        $htmlpath = $this->departPath($htmlfile);
        $htmlname = empty($name) ? $this->getName() : $name;
        $tplfile = VIEWS_PATH . '/' . $htmlpath . $htmlname . '.' . $htmlfile . '.php';
        if (!file_exists($tplfile)) {
            $parm['title'] = '文件不存在';
            $parm['msg'] = $tplfile;
            $this->showErr($parm);
        }
        if (is_array($data)) { // 先判断数组是否为空
            foreach ($data as $key => $d) {
                $$key = $d;
            }
        }
        include($tplfile);
    }

    function loadLib() {
        $num = func_num_args();
        $list = func_get_args();
        for ($i = 0; $i < $num; $i++) {
            $libname = $list[$i];
            if (empty($this->$libname)) {
                $path = $this->departPath($libname);
                $file = LIB_PATH . "/{$path}{$libname}.class.php";
                if (!include_once($file)) {
                    $parm['title'] = '文件不存在';
                    $parm['msg'] = $file;
                    $this->showErr($parm);
                }
                $this->$libname = new $libname();
            }
        }
        return true;
    }

    function loadApp() {
        $num = func_num_args();
        $list = func_get_args();
        for ($i = 0; $i < $num; $i++) {
            $appname = $list[$i];
            if (empty($this->$appname)) {
                $path = $this->departPath($appname);
                $file = APP_PATH . "/{$path}{$appname}.class.php";
                if (!include_once($file)) {
                    $parm['title'] = '文件不存在';
                    $parm['msg'] = $file;
                    $this->showErr($parm);
                }
                $this->$appname = new $appname($this->db);
            }
        }
        return true;
    }

    function departPath(&$con, $split = '/') { // 抽取路径
        $parms = explode($split, $con);
        $parmsnum = count($parms) - 1;
        if ($parmsnum > 0) {
            $con = $parms[$parmsnum];
            unset($parms[$parmsnum]);
            return implode('/', $parms) . '/';
        }
        return '';
    }

    function logit($log, $file, $date = '') { // 记录log到文件中
        $path = $this->departPath($file);
        $date = empty($date) ? date('Y-m-d') : $date;
        $logfile = LOG_PATH . '/' . $path . $file . '.' . $date . '.txt';
        $f = fopen($logfile, 'a');
        fwrite($f, $log);
        fclose($f);
    }

    function showErr($parm) { // 统一显示错误
        echo $parm['title'], '<br/>', $parm['msg'];
        exit;
    }

    // ==================================  加载时使用

    private function extractUri() { // 解包所有get参数，用引用的方式快点， 主要是获取是哪个app处理
        $theUri = array();
        $addr = explode('/', $this->getServers('PATH_INFO'));
        unset($addr[0]);
        $account = count($addr);
        if (empty($addr[$account])) {
            unset($addr[$account--]);
        }
        if ($account == 0) {
            $parm['title'] = '地址错误';
            $parm['msg'] = '请检查地址';
            $this->showErr($parm);
        } else if ($account == 1) { // 一个参数的肯定就是对应的app类名
            $theUri['classname'] = $addr[1];
            $theUri['funcname'] = 'index';
            $addr = array();
        } else {
            $func = explode('.', $addr[$account]);
            $funcaccount = count($func);
            if (($funcaccount > 1) && ($func[$funcaccount - 1]) == 'html') {
                $theUri['classname'] = $addr[$account - 1];
                unset($func[$funcaccount - 1]);
                $theUri['funcname'] = implode('.', $func);
                unset($addr[$account--]);
                unset($addr[$account--]);
            } else {
                $theUri['classname'] = $addr[$account];
                $theUri['funcname'] = 'index';
                unset($addr[$account--]);
            }
        }

        if (empty($addr)) {
            $theUri['filepath'] = 'app/' . $theUri['classname'] . '.class.php';
        } else {
            $theUri['filepath'] = 'app/' . implode('/', $addr) . '/' . $theUri['classname'] . '.class.php';
        }

        if (empty($theUri['classname']) || empty($theUri['funcname'])) { // 如果都没有指定，就跳转到默认页面
            $parm['title'] = '地址错误';
            $parm['msg'] = '请检查地址';
            $this->showErr($parm);
        }
        $theUri['classname'].='_controller';
        $theUri['funcname'].='_action';
        return $theUri;
    }

    function load() {
        $theUri = $this->extractUri();
        $prex = substr($theUri['classname'], 0, 1);
        switch ($prex) {
            case "m": {
                    require(CORE_PATH . '/wxindex.php');
                    break;
                }
            case "p": {
                    require(CORE_PATH . '/cindex.php');
                    break;
                }
            default: {
                    break;
                }
        }
        if (is_file(ROOT_PATH . '/' . $theUri['filepath'])) {
            include_once(ROOT_PATH . '/' . $theUri['filepath']);
        } else {
            $parm['title'] = '找不到指定路径';
            $parm['msg'] = ROOT_PATH . '/' . $theUri['filepath'];
            $this->showErr($parm);
        }
        try {
            if (empty($_POST)) {
                $ct = $this->getServers('CONTENT_TYPE');
                if ($ct === 'application/json') {

                    $_POST = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
                }
            }
            $app = new $theUri['classname']($theUri);
            $app->$theUri['funcname']();
        } catch (Exception $e) {
            $parm['title'] = '运行错误';
            $parm['msg'] = $e->getTraceAsString();
            $this->showErr($parm);
        }
    }

}
