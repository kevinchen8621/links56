<?php

class mcuserinfo extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'account');
    }

    function getKeys() {
        return array('id', 'mobile', 'pw', 'pwstrong', 'wechatid', 'role', 'status', 'last_signin_time', 'current_signin_time', 'register_time', 'message_number', 'note');
    }

    function insertUserInfo($parm) {
        return $this->insert($this->filter($this->getKeys(), $parm));
    }

    public function getUserInfoByMobile($mobile) { // 
        $sql = "SELECT * FROM `account` WHERE `mobile`='{$mobile}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function getUserInfoByOpenid($openid) { // 
        $sql = "SELECT * FROM `account` WHERE `wechatid`='{$openid}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function getUserInfo($parm) { // 获取用户信息
        if (!empty($parm['mobile'])) {
            $mobile = $parm['mobile'];
            $sql = "SELECT * FROM `account` WHERE `mobile`='{$mobile}' LIMIT 1";
        } else if (!empty($parm['openid'])) {
            $openid = $parm['openid'];
            $sql = "SELECT * FROM `account` WHERE `wechatid`='{$openid}' LIMIT 1";
        } else {
            return null;
        }
        return $this->db->fetch_first($sql);
    }

}
