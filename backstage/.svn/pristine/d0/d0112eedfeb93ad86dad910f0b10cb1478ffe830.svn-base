<?php

class orderinfo_controller extends cbase{
    //getname
    function getName(){return 'orderinfo';}

    //获取订单详情
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

        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        //检测必要信息
        $orderId = $this->xbase->checkpost('orderId');
        $rc = $this->shippersql-> get_order_by_id($orderId);
        if(empty($rc)){
            $this -> xbase ->failed(99,'错误的ORDERID 查询失败');
        }

        //shipper_goods_list
        $rc2 = $this -> shippersql -> get_shipper_goods_list($orderId);
        if(empty($rc2)){
            $this ->xbase->failed(99,'空的货物集');
        }

        $rc3 = $this -> shippersql -> get_shipper_amount_list($orderId);
        if(empty($rc3)){
            $this ->xbase->failed(99,'空的记录集');
        }
        $rc4 = $this -> shippersql -> get_order_status_list($orderId);
        if(empty($rc4)){
            $this ->xbase->failed(99,'空的记录集');
        }

        $result = array();

        $result['orderId'] = $orderId;
        if($rc[0]['shipperid']==$shipper_staff['shipperid']){
            $result['isOwner'] =1;
        }else{
            $result['isOwner'] =0;
        }
        $result['orderStatusId'] = $rc[0]['status'];
        $result['orderStatus'] = $this->get_status($rc[0]['status']);
        if($result['isOwner'] ==1){
            $result['orderPric']=$rc[0]['payment'];
            $result['insuranceAmount'] = $rc[0]['insurance_amount'];
        }
        $result['sender'] = array();
        $result['sender']['company'] = $rc[0]['sender_company'];
        $result['sender']['name'] = $rc[0]['sender_name'];
        $result['sender']['mobile'] = $rc[0]['sender_mobile'];
        $result['sender']['areaIdList'] = $rc[0]['sender_provinceid'].','.$rc[0]['sender_cityid'].','.$rc[0]['sender_districtid'];
        $result['sender']['areaList'] = $rc[0]['sender_province'].','.$rc[0]['sender_city'].','.$rc[0]['sender_district'];
        $result['sender']['detailAddress'] = $rc[0]['sender_address'];

        $result['receiver'] = array();
        $result['receiver']['company'] = $rc[0]['receiver_company'];
        $result['receiver']['name'] = $rc[0]['receiver_name'];
        $result['receiver']['mobile'] = $rc[0]['receiver_mobile'];
        $result['receiver']['areaIdList'] = $rc[0]['receiver_provinceid'].','.$rc[0]['receiver_cityid'].','.$rc[0]['receiver_districtid'];
        $result['receiver']['areaList'] = $rc[0]['receiver_province'].','.$rc[0]['receiver_city'].','.$rc[0]['receiver_district'];
        $result['receiver']['detailAddress'] = $rc[0]['receiver_address'];

        $result['productList'] = $this -> dexarr($rc2 );
        $result['requirePickupTime'] = $rc[0]['sender_require_post_time'];
        $result['requireArriveTime'] = $rc[0]['receiver_require_arrive_time'];
        $result['isBuyInsurance'] = $rc[0]['is_buy_insurance'];

        $result['costList'] = $this -> defarr($rc3);
        $result['trackList'] = $this -> derarr($rc4);
        //输出
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success($result);
    }

    function derarr($arr){
        $wq = array();
        foreach ($arr as $k=>$v){
            $wq[$k]['description'] = $v['update_time'];
            $wq[$k]['date'] = $v['note'];
        }
        return $wq;
    }

    function defarr($arr){
        $wq = array();
        foreach ($arr as $k=>$v){
            $wq[$k]['costName'] = $v['amount_name'];
            $wq[$k]['cost'] = $v['amount'];
        }
        return $wq;
    }

    function dexarr($arr){
        $wq = array();
        foreach ($arr as $k=>$v){
            $wq[$k]['productName'] = $v['name'];
            $wq[$k]['count'] = $v['number'];
            $wq[$k]['unitWeight'] =$v['weight'];
            $wq[$k]['unitLength'] =$v['length'];
            $wq[$k]['unitWIdth'] =$v['width'];
            $wq[$k]['unitHeight'] = $v['height'];
        }
        return $wq;
    }

    function get_status($id){
        if($id ==1){
            return '待确认';
        }
        if($id ==2){
            return '做运输计划';
        }
        if($id ==3){
            return '待派车';
        }
        if($id ==4){
            return '待提货';
        }
        if($id ==5){
            return '货品运输中';
        }
        if($id ==6){
            return '货品已到达，未支付';
        }
        if($id ==7){
            return '订单部分支付';
        }
        if($id ==8){
            return '订单已结束';
        }
        if($id ==9){
            return '订单已取消';
        }
        if($id ==10){
            return '订单已关闭';
        }
    }
}