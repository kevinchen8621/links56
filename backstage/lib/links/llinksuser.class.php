<?php

class llinksuser { // 用户信息

    public function __construct() {
        
    }

    public function signin($parm) { // 登录
//        $data['username'] = $parm['username'];
//        $data['password'] = $parm['password'];
        return $this->send('/api/user/signin', $parm);
    }

    public function forgetpassword($parm) { // 修改密码
//        $data['mobile'] = $parm['mobile']; // 手机
//        $data['sms'] = $parm['sms']; // 验证码
//        $data['password'] = $parm['password']; // 密码
        return $this->send('/api/user/signin_by_mobile', $parm);
    }

    public function signup($parm) { // 注册用户
//        $data['mobile'] = $parm['mobile']; // 手机
//        $data['sms'] = $parm['sms']; // 验证码
//        $data['password'] = $parm['password']; // 密码
        return $this->send('/api/user/signup_by_mobile', $parm);
    }

    public function resetPW($parm) { // 重设密码
//        $data['password'] = $parm['password']; // 密码
        $token = $parm['token'];
        unset($parm['token']);
        return $this->send('/api/user/reset_pass', $parm, $token);
    }

    public function verify($parm) { // 发送验证码
        $data['mobile'] = $parm['mobile'];
        $data['org'] = '邻客物流';
        return $this->send('/api/sms/verify', $data);
    }

    public function setProfile($parm) { // 设置用户数据
        $token = $parm['token'];
        unset($parm['token']);
        return $this->send('/api/user/set_profile', $parm, $token);
    }

    public function me($parm) { // 获取用户数据
        $token = $parm['token'];
        unset($parm['token']);
        return $this->get('/api/user/me', $token);
    }

    private function send($path, $data, $token = null) {
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

}
