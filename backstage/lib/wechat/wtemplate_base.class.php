<?php

class wtemplate_base { // 模板相关接口

    protected $filter = false;

    public function __construct() {
        if (include_once(HELP_PATH . '/filter.class.php')) {
            $this->filter = new filter();
        }
    }

    function test() {
        //    $customer = new template($appid, $appsecret);
//    $msg = array();
//    $msg['name'] = '客服通知';
//    $msg['remark'] = '您好，我是太平洋客服，我的微信号是faaceyu';
//    $msg['remark'] .= "\n\n" . '后台显示您用太卡购买咖啡后没出订单，请加我的微信号，确定您的信息正确后会把相关金额返还到您的太卡中。 ';
//    $msg['remark'] .= "\n\n" . '为您带来的不便敬请谅解。我们会更加努力为您服务的。谢谢您的支持，祝您生活愉快！';
//    $rc = $customer->sendBuyOk($openid, $msg, 'http://www.baidu.com');
//    var_dump($rc);
    }

    protected function send($openid, $data) {
        if (!include_once('wtoken.class.php'))
            return false;

        $data['data'] = $this->format($data['data']);
        $data = preg_replace("#\\\u([0-9a-f]{4}+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", json_encode($data));
        if (!empty($this->filter)) { // 如果需要过滤就过滤
            $this->filter->setOpenid($openid);
            $data = $this->filter->filt($data);
        }

        return $this->realSend($data);
    }

    protected function realSend($data, $again = 0) {
        $wtoken = new wtoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $wtoken->getToken($again);
        $context = array('http' => array('method' => "POST", 'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/x-www-form-urlencoded", 'content' => $data));
        $stream_context = stream_context_create($context);
        $rc = file_get_contents($url, FALSE, $stream_context);
        if ($again < 1) {
            $res = json_decode($rc, true);
            if ('' . $res['errcode'] == '40001') {
                return $this->realSend($data, ++$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        $this->logit($rc, $data);
        return $rc;
    }

    protected function format($msg) {
        $mm = array();
        foreach ($msg as $key => $m) {
            if (is_array($m)) {
                $mm[$key] = $m;
                continue;
            }
            $mm[$key] = array('value' => $m, 'color' => '#173177');
        }
        return $mm;
    }

    private function logit($rc, $data) {
        hlog::logFile(date('d H:i:s') . '|||' . $rc . '|||' . $data . "\r\n", 'log', 'wtemplate', date('Y-m'));
    }

}
