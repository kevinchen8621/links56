<?php

// 临时码分配机制
// 1亿以上 ：100000000,也就是 9位
// 9xxxxxxxx:为后台登录所用，机制为 9 AA BBBBBB AA为两个随机码， BBBBBB为当前时间的最后6位

class wqrcode { // 生成带参数的二维码

    public function __construct() {
        
    }

    function test() { // http://weixin.buick.com.cn/buick/index.php?cl=wqrcode&fun=test
        $url = $this->getQRCodeUrl(10001, true);
        var_dump($url);
        if (!empty($url)) {
            echo '<script>alert("ok");</script>';
            header('Location: ' . $url);
        }
    }

    function getQRCodeUrl($sid, $limit = 'false', $exseconds = 1800) { //  $limit = false 为临时二维码，true为永久二维码
        $data = $this->getTicket($sid, $limit, $exseconds);
        $t = json_decode($data, true);
        return empty($t['ticket']) ? false : 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $t['ticket'];
    }

    private function getTicket($sid, $limit = 'false', $exseconds = 1800) {
        if (!include_once('wtoken.class.php'))
            return false;

        $data = array();
        if ($limit) {
            $data['action_name'] = 'QR_LIMIT_SCENE';
            $data['action_info'] = array('scene' => array('scene_id' => $sid));
        } else {
            $data['expire_seconds'] = $exseconds;
            $data['action_name'] = 'QR_SCENE';
            $data['action_info'] = array('scene' => array('scene_id' => $sid));
        }
        $send = json_encode($data);

        return $this->send($send);
    }

    private function send($data, $again = 0) {
        $wtoken = new wtoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $wtoken->getToken($again);
        $context = array(
            'http' => array(
                'method' => "POST",
                'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/x-www-form-urlencoded",
                'content' => $data
            )
        );
        $stream_context = stream_context_create($context);
        $rc = file_get_contents($url, FALSE, $stream_context);
        if ($again < 1) {
            $res = json_decode($rc, true);
            if ('' . @$res['errcode'] == '40001') {
                return $this->send($data, ++$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        return $rc;
    }

}

?>