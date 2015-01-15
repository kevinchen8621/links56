<?php

class mdemo extends cmodule{

    function __construct($db) {
        parent::__construct($db, 'account');
    }

    function getKeys() {
        return array('id', 'mobile', 'pw', 'wechatid', 'role', 'status', 'last_signin_time', 'current_signin_time', 'register_time', 'message_number', 'note');
    }

    function insertUserInfo($parm) {
        return $this->insert($this->filter($this->getKeys(), $parm));
    }

//    public function getUserInfo($parm) { // 获取用户信息
//        if (!empty($parm['mobile'])) {
//            $mobile = $parm['mobile'];
//            $sql = "SELECT * FROM `account` WHERE `mobile`='{$mobile}'";
//        } else if (!empty($parm['openid'])) {
//            $openid = $parm['openid'];
//            $sql = "SELECT * FROM `account` WHERE `wechatid`='{$openid}'";
//        } else {
//            return null;
//        }
//        return $this->db->fetch_first($sql);
//    }

    public function test($parm) {
        $mobile = $parm['mobile'];
        return $this->getList("`mobile`='{$mobile}'", 0, 2, 'account');
    }

}
