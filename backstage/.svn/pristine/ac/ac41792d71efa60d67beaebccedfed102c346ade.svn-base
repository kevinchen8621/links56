<?php

class lwuserinfo { // 获取微信用户信息

    function test() { // 测试专用 http://localhost:1717/wechat2/index.php?cl=wuserinfo&fun=test
        $openid = 'oOkxst_Su1fRtwctOZ4gj9SQ4Jls'; // faace
        $res = $this->getInfo($openid);
        var_dump($res);
    }

    function getInfoP($parm) {
        return $this->getInfo($parm['openid']);
    }

    function getInfo($openid, $again = 1) {
        if (empty($GLOBALS['env']['lwtoken'])) {
            if (!include_once('lwtoken.class.php'))
                return false;
            $wtoken = $GLOBALS['env']['lwtoken'] = new lwtoken();
        } else {
            $wtoken = $GLOBALS['env']['lwtoken'];
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $wtoken->getToken($again) . '&openid=' . $openid . '&lang=zh_CN';
        $rc = file_get_contents($url);
        $res = json_decode($rc, true);
        if ($again > 0) {
            if (@$res['errcode'] == '40001') {
                return $this->getInfo($openid, --$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        return $res;
    }
}
