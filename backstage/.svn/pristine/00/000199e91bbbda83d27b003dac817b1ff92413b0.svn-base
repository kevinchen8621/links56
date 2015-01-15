<?php

class orderchange_controller extends cbase{
    //getname
    function getName(){return 'orderchange';}

    //修改订单
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
        //信息处理
        $sender = $this->xbase->checkpost('sender');
        $receiver = $this->xbase->checkpost('receiver');
        $productList = $this ->xbase->checkpost('productList');
        $requirePickupTime= $this -> xbase->checkpost('requirePickupTime');
        $requireArriveTime = $this -> xbase ->checkpost('requireArriveTime');
        $isBuyInsurance = $this ->xbase ->checkpost('isBuyInsurance');
        $insuranceAmount = $this ->xbase -> checkpost('insuranceAmount');
        $orderid = $this ->xbase -> checkpost('orderId');

        $sender_add=  explode(',', $sender['areaList']);
        $sender_addid = explode(',', $sender['areaIdList']);
        $receiver_add=  explode(',', $receiver['areaList']);
        $receiver_addid = explode(',', $receiver['areaIdList']);
        $this -> xbase ->len3($sender_add);
        $this -> xbase ->len3($sender_addid);
        $this -> xbase ->len3($receiver_add);
        $this -> xbase ->len3($receiver_addid);
        //数据库操作
        $this->loadModule('links/printid');
        $printid = $this -> printid -> get();

        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        $arr2 = array();
        $arr2['print_orderid'] = $printid;
        $arr2['shipperid']= $shipper_staff['shipperid'];
        $arr2['shipper_name'] = $shipper_staff['name'];
        $arr2['accountid'] = $userinfo['id'];
        $arr2['source'] = 1;
        $arr2['status'] =1;
        $arr2['update_time'] = date('Y-m-d H:i:s');
        $arr2['update_accountid'] = $userinfo['id'];
        $arr2['create_time'] = date('Y-m-d H:i:s');
        $arr2['carry_type']=2;
        $arr2['is_buy_insurance']=$isBuyInsurance;
        $arr2['total_value']=$insuranceAmount;
        $arr2['sender_name']=$sender['name'];
        $arr2['sender_mobile']=$sender['mobile'];
        $arr2['sender_province']=$sender_add[0];
        $arr2['sender_city']=$sender_add[1];
        $arr2['sender_district']=$sender_add[2];
        $arr2['sender_provinceid']=$sender_addid[0];
        $arr2['sender_cityid']=$sender_addid[1];
        $arr2['sender_districtid']=$sender_addid[2];
        $arr2['sender_address']=$sender['detailAddress'];
        $arr2['sender_company']=$sender['company'];
        $arr2['sender_require_post_time']=$requirePickupTime;
        $arr2['receiver_name']=$receiver['name'];
        $arr2['receiver_mobile']=$receiver['mobile'];
        $arr2['receiver_province']=$receiver_add[0];
        $arr2['receiver_city']=$receiver_add[1];
        $arr2['receiver_district']=$receiver_add[2];
        $arr2['receiver_provinceid']=$receiver_addid[0];
        $arr2['receiver_cityid']=$receiver_addid[1];
        $arr2['receiver_districtid']=$receiver_addid[2];
        $arr2['receiver_address']=$receiver['detailAddress'];
        $arr2['receiver_company']=($receiver['company'])?$receiver['company']:'';
        $arr2['receiver_require_arrive_time']=$requireArriveTime;

        if(empty($productList)){
            $this -> xbase ->failed(99,'空的商品信息');
        }

        $rc = $this -> shippersql -> update_order_by_main($arr2,$orderid);
        if(empty($rc)){
            $this -> xbase -> failed(99,'数据库写入失败');
        }

        $arr3 = $this -> decode_product($productList,$orderid);
        $rx = $this->shippersql->del_shipper_goods_list($orderid);
        if(empty($rx)){$this->xbase->failed(99,'数据库写入失败');};
        foreach($arr3 as $k=>$v){
            $rc2 = $this -> shippersql -> set_shipper_goods_list($v);
            if(empty($rc2)){
                $this -> xbase -> failed(99,'数据库写入失败');
            }
        }

        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }


    function decode_product($arr,$orderid){
        $result = array();
        foreach($arr as $k => $v){
            $result[$k]['name'] = $v['productName'];
            $result[$k]['number'] = $v['count'];
            $result[$k]['weight'] =$v['unitWeight'];
            $result[$k]['length']=$v['unitLengtht'];
            $result[$k]['width']=$v['unitWIdtht'];
            $result[$k]['height']=$v['unitHeight'];
            $result[$k]['total_weight']=$v['unitWeight']*$v['count'];
            $result[$k]['total_volume']= $v['unitLengtht']*$v['unitWIdtht']*$v['unitHeight'];
            $result[$k]['orderid'] = $orderid;
        }
        return $result;
    }
}