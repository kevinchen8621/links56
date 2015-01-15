<?php

class mctrucker extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'trucker');
    }

    function getKeys() {
        return array('id', 'mobile', 'pw', 'wechatid', 'role', 'status', 'last_signin_time', 'current_signin_time', 'register_time', 'message_number', 'note');
    }

    public function getTruckerByAccountid($accountid) { // 
        $sql = "SELECT * FROM `trucker` WHERE `accountid`='{$accountid}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function getTruckerAuthByAccountid($accountid) { // 
        $sql = "SELECT * FROM `trucker_auth` WHERE `accountid`='{$accountid}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function getBanksByAccountid($accountid) { // 
        $sql = "SELECT * FROM `trucker_bank_list` WHERE `accountid`={$accountid} LIMIT 0, 1000";
        return $this->db->select($sql);
    }

    public function getTruckByTruckid($truckid) { // 
        $sql = "SELECT * FROM `truck` WHERE `id`='{$truckid}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    public function updateHead($accountid, $trucker_picurl) {
        $this->update(array('trucker_picurl' => $trucker_picurl), 'accountid=' . $accountid, 'trucker');
        return $this->update(array('trucker_picurl' => $trucker_picurl), 'accountid=' . $accountid, 'trucker_auth');
    }

}
