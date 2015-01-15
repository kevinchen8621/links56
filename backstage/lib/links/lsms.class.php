<?php

class lsms { // 发送短信

    public function __construct() {
        
    }

    public function sendSMS($code, $mobile) { // 发送短信到指定手机
        if (empty($code) || strlen($code) < 4) {
            return buildRC('验证码错误', '-1');
        }
        if (empty($mobile) || strlen($mobile) < 11) {
            return buildRC('手机号码错误', '-2');
        }
        global $env;
        $content = urlencode('验证码：' . $code . '【' . $env['cnname'] . '】');
        $url = 'http://sms.bechtech.cn/Api/send/data/json?accesskey=810&secretkey=7dbd85f5dd091625aa86870e91e96147328b87fa&content=' . $content . '&mobile=' . $mobile;
        $rc = file_get_contents($url);
        return json_decode($rc, true);
    }

    private function buildRC($text, $errcode) {
        return array('rc' => $errcode, 'msg' => $text);
    }

}
