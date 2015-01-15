<?php

class shippersql extends cmodule {
    private $token ='';
    private $cachebase;

    public function clean(){
        $this->memcache->set($this->token, null);
    }

    public function set_global($token,$base) {
        $this->initMemcache();
        $st = str_split($token, 32);
        $token = $st[1];
        $this ->token = $token;
        $this ->cachebase = $base;
    }

    public function get_shipper_staff($accountid){
        if(empty($this->cachebase['shipper_staff'])){
            $result = $this->getList("`accountid`='{$accountid}'", 0, 1, 'shipper_staff');
            $this->memcache_set('shipper_staff',$result[0]);
            return $result[0];
        }else{
            return $this->cachebase['shipper_staff'];
        }
    }

    public function  set_order_by_image($arr){
        $rc = $this->insert($arr,'order');
        return $rc;
    }

    public function  set_order_by_main($arr){
        $rc = $this->insert($arr,'order');
        return $rc;
    }

    public function set_shipper_goods_list($arr){
        $rc = $this->insert($arr,'shipper_goods_list');
        return $rc;
    }

    public function set_new_message($arr){
        $rc = $this ->insert($arr,'message');
        return $rc;
    }

    public function update_order_by_main($arr,$orderid){
        $rc = $this ->update($arr,"`id`='".$orderid."'",'order');
        return $rc;
    }

    public function del_shipper_goods_list($orderid){
        $rc = $this ->delete("`orderid`='".$orderid."'",'shipper_goods_list');
        return $rc;
    }

    public function update_message_read($id){
        $arr = array();
        $arr['is_read'] =1;
        $rc = $this ->update($arr,"`id`='".$id."'",'message');
        return $rc;
    }

    public function update_shipper_staff_notice_list($arr,$accountid){
        $rc = $this ->update($arr,"`accountid`='".$accountid."'",'shipper_staff');
        return $rc;
    }

    public function get_shipper_staff_notice_list($accountid){
        $rc = $result = $this->getList("`accountid`='{$accountid}'",0,1,'shipper_staff');
        return $rc[0];
    }

    public function update_account_message($id,$len){
        $arr = array();
        $arr['message_number'] =$len;
        $rc = $this ->update($arr,"`id`='".$id."'",'account');
        return $rc;
    }

    public function rebulid_message($accountid){
        $rc = $result = $this->getListall("`accountid`='{$accountid}' AND `is_read`='0' AND `type`='1'",'message');
        if(empty($rc)){
            $len = 0;
        }else{
            $len = count($rc);
        }
        return $len;
    }

    public function get_shipper($id){
        if(empty($this->cachebase['shipper'])){
            $result = $this->getList("`shipperid`='{$id}'", 0, 1, 'shipper');
            $this->memcache_set('shipper',$result[0]);
            return $result[0];
        }else {
            return $this->cachebase['shipper'];
        }
    }

    public function get_shipper_contact($id,$method,$pg,$size){
        $result = $this->getList("`shipperid`='{$id}' AND (`type`='0' OR `type`='".$method."')", $size*$pg, $size, 'shipper_contact');
        return $result;
    }

    public function get_order_by_id($id){
        $result = $this->getList("`id`='{$id}'", 0, 1, 'order');
        return $result;
    }

    public function get_shipper_goods_list($orderid){
        $result = $this->getListall("`orderid`='{$orderid}'",'shipper_goods_list');
        return $result;
    }

    public function get_shipper_amount_list($orderid){
        $result = $this->getListall("`orderid`='{$orderid}'",'shipper_amount_list');
        return $result;
    }

    public function get_order_status_list($orderid){
        $result = $this->getListall("`orderid`='{$orderid}'",'order_status_list');
        return $result;
    }

    public function get_order_by_sql($where,$pg,$size){
        $result = $this->getList($where, $size*$pg, $size, 'order');
        return $result;
    }

    public function update_shipper($arr,$shipperid){
        $rc = $this ->update($arr,"`shipperid`='".$shipperid."'",'shipper');
        $this -> memcache_set('shipper',null);
        return $rc;
    }

    public function update_order($arr,$orderid){
        $rc = $this ->update($arr,"`id`='".$orderid."'",'order');
        return $rc;
    }

    public function update_shipper_staff($arr,$accountid){
        $rc = $this ->update($arr,"`accountid`='".$accountid."'",'shipper_staff');
        $this -> memcache_set('shipper_staff',null);
        $this -> memcache_set('shipper',null);
        return $rc;
    }

    public function update_shipper_auth($arr,$shipperid){
        $rc = $this ->update($arr,"`shipperid`='".$shipperid."'",'shipper_auth');
        $this -> memcache_set('shipper_staff',null);
        return $rc;
    }

    public function get_shipper_auth($shipperid){
        $result = $this->getList("`shipperid`='{$shipperid}'",0,1,'shipper_auth');
        return $result;
    }

    public function set_shipper_auth($arr){
        $rc = $this ->insert($arr,'shipper_auth');
        return $rc;
    }

    public function set_shipper_staff($arr){
        $rc = $this ->insert($arr,'shipper_staff');
        return $rc;
    }

    public function set_shipper($arr){
        $rc = $this ->insert($arr,'shipper');
        return $rc;
    }

    public function set_shipper_contact($arr){
        $rc = $this ->insert($arr,'shipper_contact');
        return $rc;
    }

    public function del_shipper_contact($shipperid,$id){
        $rc = $this ->delete("`shipperid`='".$shipperid."' AND "."`id`='".$id."'",'shipper_contact');
        return $rc;
    }

    public function update_shipper_contact($arr,$shipperid,$id){
        $rc = $this ->update($arr,"`shipperid`='".$shipperid."' AND "."`id`='".$id."'",'shipper_contact');
        return $rc;
    }

    public function update_waybill_eva($arr,$accountid,$orderid){
        $rc = $this ->update($arr,"`account`='".$accountid."' AND "."`orderid`='".$orderid."'",'waybill');
        return $rc;
    }

    public function get_message_unread($accountid){
        $result = $this->getListall("`accountid`='{$accountid}' AND `is_read`='0' AND `source`='1'",'message');
        return $result;
    }

    public function memcache_set($k,$v){
        if(empty($k) || empty($this->cachebase)){
            return;
        }
        $this ->cachebase[$k]=$v;
    }

    public function address_get($arr){
        $result1 = $this->getList("`id`='{$arr[0]}'", 0, 1, 'province');
        $result2 = $this->getList("`id`='{$arr[0]}'", 0, 1, 'city');
        $result3 = $this->getList("`id`='{$arr[0]}'", 0, 1, 'district');
        if(empty($result1) || empty($result2) || empty($result3)){
            return false;
        }
        return array($result1['id'],$result2['id'],$result3['id']);
    }

    public  function memcache_save(){
        if(!empty($this->cachebase)){
            $this->memcache->set($this->token, $this->cachebase);
        }
    }

    //测试用
    public function cleanx($key){
        $this -> memcache_set($key,null);
        $this -> memcache_save();
    }
}