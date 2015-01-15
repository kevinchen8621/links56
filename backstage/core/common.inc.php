<?php



// 初始化session
require(INC_PATH . '/session/session_operator_native.class.php');
$session = new session_operator_native();
$GLOBALS['session'] = $session;

$session->session_start();
if (!isset($_GET['nodb']) && !isset($_GET['no'])) {// 初始化数据库连接
    require(INC_PATH . '/mysql.class.php');
    $db = new mysql();
    $db->connect($env['dbhost'], $env['dbuser'], $env['dbpw'], $env['dbname']);

    $GLOBALS['db'] = $db;
}
$GLOBALS['env'] = $env;
