<?php

class wcustomer { // 客服相关接口

    public function __construct() {
//        $this->filter = $filter;
        if (include_once(HELP_PATH . '/hfilter.class.php')) {
            $this->hfilter = new hfilter();
        }
    }

    function test() { // http://weixin.buick.com.cn/wechat2/index.php?cl=wcustomer&fun=test
        $openid = 'oAtNguPrrueCR_LNcFcdzNc2MWYI'; // faace
        $res = $this->sendText($openid, 'test');
        var_dump($res);

        $news = array();
        $news[] = array('title' => 'a', 'description' => 'b', 'url' => 'http://www.baidu.com', 'picurl' => 'http://news.baidu.com/resource/img/logo_news_137_46.png');
        $news[] = array('title' => 'a', 'description' => 'b', 'url' => 'http://www.baidu.com', 'picurl' => 'http://news.baidu.com/resource/img/logo_news_137_46.png');
        $res = $this->sendNews($openid, $news); // news:title, description, url, picurl
        var_dump($res);
    }

    function simTemplate($parm) {
        $text = '';
        foreach ($parm['msg'] as $key => $cont) {
            $text .= $key . ':' . $cont . "\n";
        }
        if (!empty($parm['url'])) {
            $text .= '详情请点击<a href="' . $parm['url'] . '"> 这里 </a>';
        }
        return $this->sendText($parm['openid'], $text);
    }

    function sendP($parm) {
        switch ($parm['type']) {
            case 'text': {
                    return $this->sendText($parm['openid'], $parm['text']);
                }
            case 'news': {
                    return $this->sendNews($parm['openid'], $parm['news']);
                }
        }
        return false;
    }

    function sendText($openid, $text) {
        $data = array();
        $data['touser'] = $openid;
        $data['msgtype'] = 'text';
        $data['text'] = array('content' => $text);
        return $this->send($openid, $data);
    }

    function sendNews($openid, $news) { // news:title, description, url, picurl
        $data = array();
        $data['touser'] = $openid;
        $data['msgtype'] = 'news';
        $data['news'] = array('articles' => $news);

        return $this->send($openid, $data);
    }

    function sendImage($openid, $id) {
        $data = array();
        $data['touser'] = $openid;
        $data['msgtype'] = 'image';
        $data['image'] = array('media_id' => $id);
        return $this->send($openid, $data);
    }

    private function send($openid, $data) {
        if (!include_once('wtoken.class.php'))
            return false;

        $data = preg_replace("#\\\u([0-9a-f]{4}+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", json_encode($data));
        if (!empty($this->hfilter)) { // 如果需要过滤就过滤
            $this->hfilter->setOpenid($openid);
            $data = $this->hfilter->filt($data);
        }

        return $this->realSend($data);
    }

    private function realSend($data, $again = 0) {
        $wtoken = new wtoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $wtoken->getToken($again);
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

    private function logit($rc, $data) {
        hlog::logFile(date('d H:i:s') . '|||' . $rc . '|||' . $data . "\r\n", 'log', 'wcustomer', date('Y-m'));
    }

}
