<?php

class hlog {

//
//    private $ff;
//
//    function __construct($file = '', $path = '') {
//        if (empty($file)) {
//            $this->ff = false;
//        } else {
//            $path = empty($path) ? '' : $path . '/';
//            $logfile = LOG_PATH . '/' . $path . $file . '.' . date('Y-m-d') . '.txt';
//            $this->ff = fopen($logfile, 'a');
//        }
//    }
//
//    function __destruct() {
//        if (!empty($this->ff)) {
//            fclose($this->ff);
//        }
//    }
//
//    function log($log, $br = "\n\r") { // 记录一创建就有的log
//        if (!empty($this->ff)) {
//            fwrite($this->ff, $log . $br);
//        }
//    }
    // inluce后，直接调用这个：hlog::logAdmin 里面的内容一定要用|隔开
    public static function logAdmin($log, $openid = '') { // 单独一个log
        if (empty($openid)) {
            global $session;
            $openid = $session->get('openid');
        }
        hlog::logFile(date('m-d H:i:s') . '|' . $log . "\n", $openid, 'admin', date('Y'));
    }

    public static function logDebug($file, $log, $date = '') { // 单独一个log
        if (is_array($log)) {
            $log = var_export($log, true);
        } else if (is_object($log)) {
            $log = var_export($log, true);
        }
        hlog::logFile("$log\n", $file, 'debug', date('Y-m-d'));
    }

    // inluce后，直接调用这个：hlog::logFile
    public static function logFile($log, $file, $path = '', $date = '') { // 单独一个log
        $path = empty($path) ? '' : $path . '/';
        $date = empty($date) ? date('Y-m-d') : $date;
        $logfile = LOG_PATH . '/' . $path . $file . '.' . $date . '.txt';
        $f = fopen($logfile, 'a');
        fwrite($f, $log);
        fclose($f);
    }

// inluce后，直接调用这个：log::logFile
    public static function logDB($openid, $type, $tag, $name, $data) { // 单独一个log
        $path = empty($path) ? '' : $path . '/';
        $logfile = LOG_PATH . '/' . $path . $file . '.' . date('Y-m-d') . '.txt';
        $f = fopen($logfile, 'a');
        fwrite($f, $log);
        fclose($f);
    }

}

?>