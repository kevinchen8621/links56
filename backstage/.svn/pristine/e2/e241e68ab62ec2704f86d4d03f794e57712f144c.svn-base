<?php

abstract class cindex extends cbase {

    function __construct() {
        parent::__construct();
    }

    abstract function getName();

    function alert($content, $url = '') { // 打开html网页
        if (!is_string($content)) {
            echo '<script>alert("' . $content . '");' . $url . '</script>';
        }
        if (strlen($url) > 3) {
            $url = 'window.location.href="' . $url . '";';
        } else if ((strlen($url) > 0) && (intval($url) != 0)) {
            $url = 'history.back(' . intval($url) . ');';
        }

        echo '<script>alert("' . $content . '");' . $url . '</script>';
        exit;
    }

    function getRandCode($length, $onlynum = false) {
        $basecode = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rc = '';
        for ($j = 0; $j < $length; $j++) {
            $i = $onlynum ? rand(0, 9) : rand(0, 61);
            $rc .= substr($basecode, $i, 1);
        }
        return $rc;
    }

    function logAdmin($log, $openid = '') { // 记录日志
        if (empty($openid)) {
            $openid = $this->session->get('openid');
        }
        $this->logit(date('m-d H:i:s') . '|' . $log, 'admin/' . $openid, date('Y'));
    }

    function sessionChange() {
        $openid = $this->session->get('openid');
        $timegap = $_SERVER['REQUEST_TIME'] - $this->session->get('timechange');
        if (empty($openid) || (abs($timegap) > 120 * 60)) { // 30分钟有效
            $this->session->set('timechange', $_SERVER['REQUEST_TIME']);
            header('LOCATION:index.php?a=manager.pmindex');
        } else { // 不断地更新时间
            $this->session->set('timechange', $_SERVER['REQUEST_TIME']);
        }
    }

    public $pagecount, $pagestart, $pagenum, $pageurl; // 分页内容

    function setPageInfo($count, $url, $start = 0, $num = 10) { // 分页内容
        $this->pagecount = $count;
        $this->pagestart = $start;
        $this->pagenum = $num;
        $this->pageurl = $url;
    }

    function getAllFuncs() {
        $initlist = array(
            array(
                'name' => '后台管理',
                'id' => 'manager',
                'children' => array(
                    array(
                        'name' => '群组管理',
                        'id' => 'manager_group',
                        'url' => 'index.php?a=manager.pmadmin.group.html',
                        'extname' => '添加',
                        'exturl' => 'index.php?a=manager.pmadmin.groupedit.html'
                    ),
                    array(
                        'name' => '管理员设置',
                        'id' => 'manager_member',
                        'url' => 'index.php?a=manager.pmadmin',
                        'extname' => '添加',
                        'exturl' => 'index.php?a=manager.pmadmin.edit.html'
                    ),
                    array(
                        'name' => '修改密码',
                        'id' => 'manager_setpw',
                        'url' => 'index.php?a=manager.pmadmin.setpw.html'
                    ),
                ),
            ),
            array(
                'name' => '基本功能',
                'id' => 'base',
                'children' => array(
                    array(
                        'name' => '文本消息',
                        'id' => 'base_text',
                        'url' => 'index.php?a=manager.pmconstmsg.text.html',
                        'extname' => '添加',
                        'exturl' => 'index.php?a=manager.pmconstmsg.textedit.html'
                    ),
                    array(
                        'name' => '图文消息',
                        'id' => 'base_news',
                        'url' => 'index.php?a=manager.pmconstmsg.news.html',
                        'extname' => '添加',
                        'exturl' => 'index.php?a=manager.pmconstmsg.newsadd.html'
                    ),
                    array(
                        'name' => '音乐消息',
                        'id' => 'base_music',
                        'url' => 'index.php?a=manager.pmconstmsg.music.html',
                        'extname' => '添加',
                        'exturl' => 'index.php?a=manager.pmconstmsg.musicedit.html'
                    ),
                    array(
                        'name' => '网页管理',
                        'id' => 'base_web',
                        'url' => 'index.php?a=manager.pmconstmsg.web.html',
                        'extname' => '添加',
                        'exturl' => 'index.php?a=manager.pmconstmsg.webedit.html'
                    ),
                    array(
                        'name' => '自定义菜单',
                        'id' => 'base_menu',
                        'url' => 'index.php?a=manager.pmconstmsg.menu.html'
                    ),
                ),
            ),
            array(
                'name' => '数据统计',
                'id' => 'statics',
                'children' => array(
                    array(
                        'name' => '关注/取消统计',
                        'id' => 'statics_subscribe',
                        'url' => '#',
                    ),
                    array(
                        'name' => '用户互动统计',
                        'id' => 'statics_log',
                        'url' => '#',
                    ),
                ),
            ),
            array(
                'name' => '其他功能',
                'id' => 'other',
                'children' => array(
                    array(
                        'name' => '微信模拟器',
                        'id' => 'other_wechat_simulater',
                        'newpage' => true, // 用新页面打开
                        'url' => 'wxindex.php?con=csimulate&no=true&url=' . urlencode($GLOBALS['env']['http'] . 'wxapi.php'),
                    ),
                    array(
                        'name' => '在线代码编辑',
                        'id' => 'other_online_php_code',
                        'newpage' => true, // 用新页面打开
                        'url' => 'index.php?con=ccode',
                    ),
                ),
            ),
        );

        if ($this->session->get('gid') == 1) {
            return $initlist;
        }
        $auth = $this->session->get('auth');
        $list = array();
        foreach ($initlist as $l) {
            if (strpos($auth, $l['id'] . ',') > 0) {
                $onelist = array();
                $onelist['name'] = $l['name'];
                $onelist['id'] = $l['id'];

                $children = array();
                foreach ($l['children'] as $ll) {
                    if (strpos($auth, $ll['id']) > 0) {
                        $c = array();
                        $c['name'] = $ll['name'];
                        $c['url'] = $ll['url'];
                        if (isset($ll['extname'])) {
                            $c['extname'] = $ll['extname'];
                            $c['exturl'] = $ll['exturl'];
                        }
                        $children[] = $c;
                    }
                }
                $onelist['children'] = $children;
                $list[] = $onelist;
            }
        }
        return $list;
    }

}
