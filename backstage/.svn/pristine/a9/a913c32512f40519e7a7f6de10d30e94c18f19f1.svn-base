<?php
header('Content-Type:text/html;charset=utf-8');
define('YSTYLE', 'YSTYLE');

/** 众所周知，当php.ini里面的register_globals=on时，各种变量都被注入代码，
 * 例如来自 HTML 表单的请求变量。再加上 PHP 在使用变量之前是无需进行初始化的。
 * 那么就有可能导致不安全，假如有人恶意发出这么一个get请求
 * "http://yourdomain/unsafe.php?GLOBALS=",那么就会清除$GLOBALS变量的值而导致不安全。
 * */
if (isset($_GET['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    exit('Invalid Request');
}

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('PRC');

// 定义所有必要路径
define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('LIB_PATH', ROOT_PATH . '/lib');
define('LOG_PATH', ROOT_PATH . '/log');
define('MODULE_PATH', ROOT_PATH . '/module');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('WEB_PATH', ROOT_PATH . '/views/custom');
define('DATA_PATH', ROOT_PATH . '/data');

// 根据输入的参数，加载对应的app
require(CONFIG_PATH . '/config.php');
require(CORE_PATH . '/ccommon.class.php');
require(CORE_PATH . '/cbase.class.php');
$cbase = new cbase();
$cbase->load();