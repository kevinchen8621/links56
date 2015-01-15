<?php

if (!defined('YSTYLE')) {
    exit('Access Denied');
}

$env = array(// faace
    'version' => '1.0.0',
    'name' => 'shipper', // 文件夹的名字，session的名字，前缀等等
    'cnname' => '邻客物流', // 微信中文名
    'appid' => 'wx2258345798565be7', //  'wxec85435e880dee34', // 'wx85bee6f9509c34b1',
    'appsecret' => 'c81de952513d8817f403c4127dd9c0c8', // 'd0a234c67a1f30124328d7eb1d87b645', // '69b9a6aaf46f2e70b9bf8e91bc62195c',
    'weixinid' => 'gh_5e846b8b8558', // 'gh_6e7bbabfcbfc', // 'gh_748dde479ab8', // 公众号id
    'host' => $_SERVER['HTTP_HOST'], // 域名
    'memcache_url' => '127.0.0.1',
    'signkey' => 'YSC_BUICK_2014',
    // 'dbhost' => '10.66.109.10', // 数据库的地址
    'dbhost' => 'www.links56.com:4040', // 数据库的地址
    'dbuser' => 'root', // 数据库用户名
    'dbpw' => 'Links56@@', // 数据库用户密码
    'dbname' => 'backstage', // 数据库名字
    'cdn' => 'http://1251157231.cdn.myqcloud.com/1251157231/links/'
        // 'dbhost' => 'www.yiwindow.com', // 数据库的地址
        // 'dbuser' => 'faace', // 数据库用户名
        // 'dbpw' => 'Ysc2014', // 数据库用户密码
        //'dbname' => 'links', // 数据库名字
        // 'cdn' => 'http://1251112478.cdn.myqcloud.com/1251112478/buick/'
);
$env['http'] = 'http://' . $env['host'] . '/' . $env['name'] . '/';
$env['webidx'] = $env['http'] . 'index.php';
