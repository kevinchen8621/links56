<?php

abstract class wxindex extends cbase {

    function __construct() {
        parent::__construct();
    }

    abstract function getName();

    function getRandCode($length, $onlynum = false) {
        $basecode = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rc = '';
        for ($j = 0; $j < $length; $j++) {
            $i = $onlynum ? rand(0, 9) : rand(0, 61);
            $rc .= substr($basecode, $i, 1);
        }
        return $rc;
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

    function alert($content, $url = '') { // 打开html网页
        echo '<head>';
        echo '    <title>', $GLOBALS['env']['cnname'], '</title>';
        echo '    <meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0" name="viewport" />';
        echo '    <link href="views/shipper/css/main.css" rel="stylesheet">';
        echo '    <link href="views/public/css/dialog.css" rel="stylesheet">';
        echo '    <script language="javascript" src="views/public/js/dialog.js"></script>';
        echo '<body></body>';
        if (!is_string($content)) {
            echo '<script type="text/javascript">dialog("' . $content . '");' . $url . '</script>';
        } else {
            if (strlen($url) > 3) {
                $url = 'location.href="' . $url . '";';
            } else if ((strlen($url) > 0) && (intval($url) != 0)) {
                $url = 'history.back(' . intval($url) . ');';
            }
            echo '<script type="text/javascript">dialog("' . $content . '", 1, function(){' . $url . '});</script>';
        }
        exit;
    }

    function confirm($content, $url = '') { // 打开html网页
        echo '<head>';
        echo '    <title>', $GLOBALS['env']['cnname'], '</title>';
        echo '    <meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0" name="viewport" />';
        echo '    <link href="views/shipper/css/main.css" rel="stylesheet">';
        echo '    <link href="views/public/css/dialog.css" rel="stylesheet">';
        echo '    <script language="javascript" src="views/public/js/dialog.js"></script>';
        echo '<body></body>';

        if (!is_string($content)) {
            echo '<script type="text/javascript">dialog("' . $content . '", 2);' . $url . '</script>';
        } else {
            if (strlen($url) > 3) {
                $url = 'location.href="' . $url . '";';
            } else if ((strlen($url) > 0) && (intval($url) != 0)) {
                $url = 'history.back(' . intval($url) . ');';
            }
            echo '<script type="text/javascript">dialog("' . $content . '",2 , function(){' . $url . '});</script>';
        }
        exit;
    }

    /**
     * [getOpenid 获取用户的微信ID]
     * 思路:
     * 1.分别从get方式或者session获取用户微信ID，有则返回
     * 2.利用授权方式强制获取微信ID
     * a.通过code和state判断是不是授权形式进入，若不是就以重定向的形式重新打开链接
     * b.若是授权就使用授权形式获取微信ID，详情查看微信开发文档的网页授权Oauth2.0
     * @return
     * string 微信ID
     * boolean false 只有以下代码的openid为空才会返回
     * if (empty($openid)) {
     *   $openid = $this->runWXLib('wauth', 'getOpenid', array('code' => $_GET['code']));
     * } 
     */
    function getOpenid($force = false) { // 可以自动生成auth链接，并再次跳转，这时会带上openid
        // return 'o-r0pt772e56wQ1HOG3bHHJEntrw';
        $openid = $this->getGets('openid');
        if (empty($openid)) { // 通过各种方式获取openid
            $openid = $this->getPosts('openid');
            if (empty($openid)) {
                $this->initSession();
                $openid = $this->session->get('openid');
                $otime = $this->session->get('openidtime');
// faacet 需要看看openid                if ((strpos(' ' . $openid, 'os') == 1) || ($otime + 30 * 1000 < time())) {
//                    $openid = '';
//                }
            }
        }
        if (empty($openid) || $force) { // 没有openid，那么。。。。
            $code = $this->getGets('code');
            $state = $this->getGets('state');
            $this->loadLib('wechat/lwauth');
            if (isset($code) && isset($state)) { // 已经是跳转过了，直接获取openid
                $openid = $this->lwauth->getOpenid(array('code' => $code));
                if (!empty($openid)) {
                    $this->updateOpenid($openid);
                    if (strpos($state, 'oi_') == 1) { // 这里我们添加为好友关系
                        $friend = substr($_GET['state'], 4, strlen($openid));
                        // faacet $this->dmyfriends->addFriends($openid, $friend);
                    }
                    return $openid;
                }
            } else { // 否者直接键权一次
                $myulr = "http://{$GLOBALS['env']['host']}{$_SERVER["REQUEST_URI"]}";
                $authurl = $this->lwauth->getUrl(array('url' => $myulr));
                header('Location: ' . $authurl);
                exit;
            }
            return false;
        }
        $this->updateOpenid($openid);
        return $openid;
    }

    function updateOpenid($openid) {
        $this->initSession();
        $this->session->set('openid', $openid);
        $this->session->set('openidtime', time());
    }

// wechat ============== begin
    private $wechat = array();

    function wxOnly() { // 调用后，如果不是微信上打开就返回空内容
        $agent = $_SERVER["HTTP_USER_AGENT"];
        $pos = strpos($agent, 'MicroMessenger');
        if ($pos < 1) {
            $this->alert('请在微信上打开');
            echo '本网页只能在微信浏览器上打开，谢谢';
            exit;
        }
    }

    function setTitle($title, $mem = true) {
        echo '<h3 class="top">';
        if (!empty($_SERVER['HTTP_REFERER'])) { // 有历史记录
            echo '<div onClick="javascript :history.back(-1);" class="back"></div>';
        }

        echo $title;
        if ($mem) {
            // echo '<a href="wxindex.php?con=cwmember"><div class="infor"></div></a>';
        }
        echo '</h3>';
    }

    function wxHideToolbar() { // 隐藏下面的工具栏（好像无效）
        $this->wechat['hideToolbar'] = true;
    }

    function wxHideOptionMenu() { // 隐藏右上角的工具栏
        $this->wechat['hideOptionMenu'] = true;
    }

    private function insertHttp($url) {
        if (strpos('#' . $url, 'http') < 1) {
            $name = $GLOBALS['env']['name'];
            $newurl = str_replace($name . '//' . $name, $name, $GLOBALS['env']['http'] . $url);
            return $newurl;
        }
        return $url;
    }

    function wxshare($picurl, $link, $desc, $title = '', $callback = false, $auth = true) { // 分享页面
        if (is_array($picurl)) {
            $picurlPYQ = $this->insertHttp($picurl['pyq']);
            $picurlPY = $this->insertHttp($picurl['py']);
            $picurlWB = $this->insertHttp($picurl['wb']);
        } else {
            if (empty($picurl)) {
                $picurl = $GLOBALS['env']['cdn'] . 'views/default/img/logoimg.jpg';
            }
            $picurlPYQ = $picurlPY = $picurlWB = $this->insertHttp($picurl);
        }

        $parm = array();
        $parm['state'] = $auth ? '_oi_' . $this->getOpenid() : 'faace';
        if (is_array($link)) {
            $parm['url'] = $this->insertHttp($link['pyq']);
            $linkPYQ = $auth ? $this->runWXLib('wauth', 'getUrl', $parm) : $parm['url'];
            $parm['url'] = $this->insertHttp($link['py']);
            $linkPY = $auth ? $this->runWXLib('wauth', 'getUrl', $parm) : $parm['url'];
            $parm['url'] = $this->insertHttp($link['wb']);
            $linkWB = $auth ? $this->runWXLib('wauth', 'getUrl', $parm) : $parm['url'];
        } else {
            if (empty($link)) { // 如果链接是空的就直接用当前链接
                $link = $_SERVER["REQUEST_URI"];
            }
            $parm['url'] = $this->insertHttp($link);
            $linkPYQ = $linkPY = $linkWB = $auth ? $this->runWXLib('wauth', 'getUrl', $parm) : $parm['url'];
            $linkWB = 'http://t.cn/RPIOlsz'; // faacet 直接就是关注页面了
        }

        if (is_array($desc)) {
            $descPYQ = $desc['pyq'];
            $descPY = $desc['py'];
            $descWB = $desc['wb'];
        } else {
            $descPYQ = $descPY = $descWB = $desc;
        }

        if (is_array($title)) {
            $titlePYQ = $title['pyq'];
            $titlePY = $title['py'];
            $titleWB = $title['wb'];
        } else {
            $titlePYQ = $titlePY = $titleWB = $title;
        }

        if (is_array($callback)) {
            $callbackPYQ = $this->insertHttp($callback['pyq']);
            $callbackPY = $this->insertHttp($callback['py']);
            $callbackWB = $this->insertHttp($callback['wb']);
        } else if (empty($callback)) {
            $callbackPYQ = $callbackPY = $callbackWB = $callback;
        } else {
            $callbackPYQ = $callbackPY = $callbackWB = $this->insertHttp($callback);
        }

        $share = array();
        $share['picurlPYQ'] = $picurlPYQ;
        $share['linkPYQ'] = $linkPYQ;
        $share['descPYQ'] = $descPYQ;
        $share['titlePYQ'] = $titlePYQ;
        $share['callbackPYQ'] = $callbackPYQ;

        $share['picurlPY'] = $picurlPY;
        $share['linkPY'] = $linkPY;
        $share['descPY'] = $descPY;
        $share['titlePY'] = $titlePY;
        $share['callbackPY'] = $callbackPY;

        $share['picurlWB'] = $picurlWB;
        $share['linkWB'] = $linkWB;
        $share['descWB'] = $descWB;
        $share['titleWB'] = $titleWB;
        $share['callbackWB'] = $callbackWB;

        $share['appid'] = $GLOBALS['env']['appid'];
        $this->wxshareP($share);
    }

    function wxshareP($share) {
        $this->wechat['share'] = $share;
    }

    function loadWXShare() { // 自动判断加载微信分享相关的内容
        if (!empty($this->wechat['share'])) {
            $this->loadHtml('public/index', $this->wechat, 'wechat');
        }
    }

}
