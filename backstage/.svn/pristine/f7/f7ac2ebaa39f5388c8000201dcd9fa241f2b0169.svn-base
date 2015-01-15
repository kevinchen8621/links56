<?php

class orderrollback_controller extends cbase{
    //getname
    function getName(){return 'orderrollback';}

    //取消订单
    //曹返
    //2015/1/11
    function index_action(){
        //检测 token
        $this->loadLib('shipper/xbase');
        $this->loadLib('links/ltoken');
        $this->loadModule('links/shippersql');
        $token = $this->xbase->header_token();
        //token参数缺失
        if ($token == '') {
            $this->xbase->failed(1, '请求参数缺失(token)');
        };
        //token check
        $pd = $this->ltoken->token_decode($token);
        if ($pd) {
            $userinfo = $this->ltoken->get('userinfo');
        } else {
            //error token
            $this->xbase->failed(3, 'token无效');
        }
        //检测货主身份及黑名单
        $this->xbase->check($userinfo);
        //sql初始化
        $this->shippersql->set_global($token, $this->ltoken->getall());
        //检测必要信息
        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        if(empty($shipper_staff)){
            $this-> xbase->failed(99,'错误的身份信息');
        }

        $orderId = $this -> xbase -> checkpost('orderId');

        $TEST = $this -> shippersql ->  get_order_by_id($orderId);
        if($TEST[0]['shipperid']!=$shipper_staff['shipperid']){
            $this -> xbase->failed(5,'该订单不属于该用户Id');
        }

        $arr =array('status'=>9);
        $rc = $this -> shippersql -> update_order($arr,$orderId);
        //输出
        if(empty($rc)){
            $this-> xbase -> failed(99,'数据库异常，写入失败');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }
}