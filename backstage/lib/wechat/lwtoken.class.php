<?php

class lwtoken { // 用来过滤关键字

    private $appid;
    private $appsecret;

    public function __construct() {
        global $env;
        $this->appid = $env['appid'];
        $this->appsecret = $env['appsecret'];
    }

    public function test() { // 测试专用 http://weixin.buick.com.cn/wechat2/index.php?cl=wtoken&fun=test
        var_dump($this->getToken());
    }

    public function getToken($force = false) {
        $file = LOG_PATH . '/token.txt';

        if (file_exists($file)) { // 如果文件存在，就先判断是否超过时间了
            if ((time() - filemtime($file) < 5400) && (empty($force))) {
                $token = file_get_contents($file);
                if (strlen($token) > 5)
                    return $token;
            }
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->appsecret;
        $gettocken = file_get_contents($url);

        $res = json_decode($gettocken, true);
        if (isset($res['access_token'])) {
            file_put_contents($file, $res['access_token']);
            return $res['access_token'];
        }
        return false;
    }

}