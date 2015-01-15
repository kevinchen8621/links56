<?php

class lwshorturl { // 生成短链接

    function getUrlP($parm) {
        return $this->getInfo($parm['url']);
    }

    function getUrl($longurl, $again = 1) {
        if (empty($GLOBALS['env']['lwtoken'])) {
            if (!include_once('lwtoken.class.php'))
                return false;
            $wtoken = $GLOBALS['env']['lwtoken'] = new lwtoken();
        } else {
            $wtoken = $GLOBALS['env']['lwtoken'];
        }
        $data = '{"action":"long2short","long_url":"' . $longurl . '"}';
        $url = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token=' . $wtoken->getToken();
        $context = array();
        $context['http'] = array(
            'method' => 'POST',
            'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/x-www-form-urlencoded",
            'content' => $data // "{\"action\":\"long2short\",\"long_url\":\"http://wap.koudaitong.com/v2/showcase/goods?alias=128wi9shh&spm=h56083&redirect_count=1\"}" // preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($data))
        );
        $rc = file_get_contents($url, false, stream_context_create($context));
        $res = json_decode($rc, true);
        if ($again > 0) {
            if (@$res['errcode'] == '40001') {
                return $this->getInfo($longurl, --$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        return isset($res['short_url']) ? $res['short_url'] : false;
    }

}
