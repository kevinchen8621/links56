<?php

class mmprofiles extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'mmprofiles');
    }

    function getKeys() {
        return array('id', 'openid', 'mobile', 'password', 'ts');
    }

    function insertUserInfo($parm) {
        return $this->insert($this->filter($this->getKeys(), $parm));
    }

    public function getUserInfo($parm) { // 获取可以配置的所有群组
        $openid = $parm['openid'];
        $sql = "SELECT * FROM `mmprofiles` WHERE `openid`='{$openid}'";
        return $this->db->fetch_first($sql);
    }

}
