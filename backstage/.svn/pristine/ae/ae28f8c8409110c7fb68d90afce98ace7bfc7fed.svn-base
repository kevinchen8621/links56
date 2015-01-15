<?php

class lfilter { // 用来过滤关键字

    private $openid = '';
    private $weixinid = '';
    private $host = '';

    public function __construct($openid = '') {
        global $env;
        $this->weixinid = $env['weixinid'];
        $this->openid = $openid;
        $this->host = 'http://' . $env['host'] . '/';
        $this->http = $env['http'];
    }

    public function setOpenid($openid) {
        $this->openid = $openid;
    }

    public function unfilt($str) {
        if (strlen($str) < 5)
            return '';
        $str = str_replace($this->weixinid, '#WEIXINID#', $str);
        $str = str_replace($this->http, '#HTTP#', $str); // 替换web入口
        $str = str_replace($this->host, '#HOST#', $str); // 替换web入口
        // $str = htmlspecialchars($str);
        return $str;
    }

    public function filt($str) {
        if (strlen($str) < 4)
            return '';
        if (!empty($this->weixinid)) {
            $str = str_replace('#WEIXINID#', $this->weixinid, $str);
        }
        if (!empty($this->openid)) {
            $str = str_replace('#OPENID#', $this->openid, $str); // #OPENID#
//            if (strpos('0' . $str, '#NICKNAME#') > 0) { // 是否有nickname关键字
//                if (include_once(WXLIB_PATH . '/wuserinfo.class.php')) {
//                    $wuserinfo = new wuserinfo();
//                    $d = $wuserinfo->getInfo($this->openid);
//                    if (isset($d['nickname'])) {
//                        $str = str_replace('#NICKNAME#', $d['nickname'], $str);
//                    }
//                }
//            }
        }

        $str = str_replace('#HTTP#', $this->http, $str); // 替换web入口
        $str = str_replace('#HOST#', $this->host, $str); // 替换web入口
        $str = str_replace('#CDN#', $GLOBALS['env']['cdn'], $str); // 替换web入口
        $str = str_replace('#TS#', time(), $str); // 时间
        // $str = str_replace('#INPUT#', $Message, $str); // 用户输入
        // $str = str_replace("\r\n", "\n", $str); // 回车
//        if ($htmlchars) {
//            $str = htmlspecialchars_decode($str);
//        }
        return $str;
    }

}
