<?php

class lshipperorders { // 我的订单

    public function __construct() {
        
    }

    public function getMyorders($parm) { // 获取我所有的订单
        $token = $parm['token'];
        unset($parm['token']);
        return $this->send('getMyorders', $parm, $token);
    }

    private function send($path, $data, $token = null) {
        return $this->send2($path, $data, $token); // 测试环境
        $url = 'http://www.links56.com' . $path;
        $http = array(
            'method' => "POST",
            'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/json",
            'content' => json_encode($data)
        );
        if (!empty($token)) {
            $http['header'] .= "\r\nAuthorization: Bearer " . $token;
        }
        $context = array('http' => $http);
        $stream_context = stream_context_create($context);
        $rc = file_get_contents($url, FALSE, $stream_context);
        $res = json_decode($rc, true);
        return $res;
    }

    private function get($path, $token = null) {
        $url = 'http://www.links56.com' . $path;
        $http = array(
            'method' => "GET",
            'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/json",
        );
        if (!empty($token)) {
            $http['header'] .= "\r\nAuthorization: Bearer " . $token;
        }
        $context = array('http' => $http);
        $stream_context = stream_context_create($context);
        $rc = file_get_contents($url, FALSE, $stream_context);
        $res = json_decode($rc, true);
        return $res;
    }

    private function send2($path, $data, $token = null) {
        switch ($path) {
            case 'getMyorders': {
                    break;
                }
        }
    }

}
