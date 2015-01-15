<?php

class mcwaybill extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'waybill');
    }

    public function getBiddingRecord($biddingid, $truckerid) { // 
        $sql = "SELECT * FROM `bidding_record` WHERE `biddingid`='{$biddingid}' AND `truckerid`='{$truckerid}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function getBiddingAllRecord($biddingid) { // 所有抢单司机
        $sql = "SELECT * FROM `bidding_record` WHERE `biddingid`='{$biddingid}' LIMIT 1, 10000";
        return $this->db->select($sql);
    }

    public function getSuccessBiddingAllRecord($biddingid) { // 所有成功抢单列表
        $sql = "SELECT * FROM `bidding_record` WHERE `biddingid`='{$biddingid}' AND `result_status`=1 LIMIT 0, 10000";
        return $this->db->select($sql);
    }

    function getTruckerLevel($accountid) { // 获取司机的星级
        $sql = "SELECT level FROM `trucker` WHERE `accountid`='{$accountid}' LIMIT 1";
        $rc = $this->db->fetch_first($sql);
        return isset($rc['level']) ? $rc['level'] : 1;
    }

    public function getBiddingById($id) { // 
        $sql = "SELECT * FROM `bidding` WHERE `id`='{$id}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function combineArea($p, $c, $d) {
        $a = $p;
        if (!empty($c)) {
            $a .= ',' . $c;
        }
        if (!empty($d)) {
            $a .= ',' . $d;
        }
        return $a;
    }

    // ======================


    public function getTruckByTruckid($truckid) { // 
        $sql = "SELECT * FROM `truck` WHERE `id`='{$truckid}'";
        return $this->db->fetch_first($sql);
    }

    public function updateHead($accountid, $trucker_picurl) {
        $this->update(array('trucker_picurl' => $trucker_picurl), 'accountid=' . $accountid, 'trucker');
        return $this->update(array('trucker_picurl' => $trucker_picurl), 'accountid=' . $accountid, 'trucker_auth');
    }

    function insertUserInfo($parm) {
        return $this->insert($this->filter($this->getKeys(), $parm));
    }

    public function getUserInfoByMobile($mobile) { // 
        $sql = "SELECT * FROM `account` WHERE `mobile`='{$mobile}'";
        return $this->db->fetch_first($sql);
    }

    public function getUserInfoByOpenid($openid) { // 
        $sql = "SELECT * FROM `account` WHERE `wechatid`='{$openid}'";
        return $this->db->fetch_first($sql);
    }

    public function getUserInfo($parm) { // 获取用户信息
        if (!empty($parm['mobile'])) {
            $mobile = $parm['mobile'];
            $sql = "SELECT * FROM `account` WHERE `mobile`='{$mobile}'";
        } else if (!empty($parm['openid'])) {
            $openid = $parm['openid'];
            $sql = "SELECT * FROM `account` WHERE `wechatid`='{$openid}'";
        } else {
            return null;
        }
        return $this->db->fetch_first($sql);
    }

}
