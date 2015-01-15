<?php

class lwauth { // 用来过滤关键字

    public function __construct() {
        
    }

    public function test() { // 以下是调用例子
//        $code = $this->getGets('code');
//        $state = $this->getGets('state');
//        $this->loadLib('wechat/lwauth', 'wechat/lwuserinfo');
//        if (empty($code)) {
//            echo '没有授权';
//            return;
//        }
//        $info = $this->lwauth->getInfo($code);
    }

    public function test2() {
        $parm = array();
        $parm['code'] = $_GET['code'];
        var_dump($this->getOpenid($parm));
    }

    public function getOpenid($parm) {
        global $env;
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $env['appid'] . '&secret=' . $env['appsecret'] . '&code=' . $parm['code'] . '&grant_type=authorization_code';
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
//        $log = curl_exec($ch);
//        curl_close($ch);
        $log = @file_get_contents($url);
        $l = json_decode($log, true);
        return isset($l['openid']) ? $l['openid'] : 0;
    }

    public function getUrl($parm) {
        global $env;
        if (empty($parm['url']))
            return false;
        if (empty($parm['state']))
            $parm['state'] = 'fw';
        $scope = (empty($parm['scope'])) ? 'snsapi_base' : $parm['scope']; // snsapi_userinfo
        $http = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $env['appid'];
        $http .='&redirect_uri=' . urlencode($parm['url']);
        $http .='&response_type=code&scope=' . $scope . '&state=' . $parm['state'] . '#wechat_redirect';
        return $http;
    }

    private function getTokenInfo($code, $force = false) { // 为auth获取的token，和另外一个token不一样
        global $env;
        $file = LOG_PATH . '/reflesh_token.txt';
//
//        if (file_exists($file)) { // 如果文件存在，就先判断是否超过时间了
//            if ((time() - filemtime($file) < 5400) && (empty($force))) {
//                $token = file_get_contents($file);
//                if (strlen($token) > 5)
//                    return $token;
//            }
//        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code=' . $code . '&appid=' . $env['appid'] . '&secret=' . $env['appsecret'];
        $gettocken = file_get_contents($url);
        $res = json_decode($gettocken, true);
        if (isset($res['access_token'])) {
            file_put_contents($file, $res['access_token']);
            return $res;
        }
        return false;
    }

    function getInfo($code, $again = 1) {
        $tokeninfo = $this->getTokenInfo($code);
        $token = $tokeninfo['access_token'];
        $openid = $tokeninfo['openid'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $token . '&openid=' . $openid . '&lang=zh_CN';

        $rc = file_get_contents($url);
        $res = json_decode($rc, true);
        if ($again > 0) {
            if (@$res['errcode'] == '40001') {
                return $this->getInfo($openid, --$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        return $res;
    }

    public function getRefreshToken($force = false) {
        $file = LOG_PATH . '/reflashtoken.txt';

        if (file_exists($file)) { // 如果文件存在，就先判断是否超过时间了
            if ((time() - filemtime($file) < 5400) && (empty($force))) {
                $token = file_get_contents($file);
                if (strlen($token) > 5)
                    return $token;
            }
        }

        global $env;
        if (empty($GLOBALS['env']['lwtoken'])) {
            if (!include_once('lwtoken.class.php'))
                return false;
            $wtoken = $GLOBALS['env']['lwtoken'] = new lwtoken();
        } else {
            $wtoken = $GLOBALS['env']['lwtoken'];
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $env['appid'] . '&grant_type=refresh_token&refresh_token=' . $wtoken->getToken($again);
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
//        $log = curl_exec($ch);
//        curl_close($ch);
        $gettocken = @file_get_contents($url);
        $res = json_decode($gettocken, true);
        if (isset($res['access_token'])) {
            file_put_contents($file, $res['access_token']);
            return $res['access_token'];
        }
        return false;
    }

}
