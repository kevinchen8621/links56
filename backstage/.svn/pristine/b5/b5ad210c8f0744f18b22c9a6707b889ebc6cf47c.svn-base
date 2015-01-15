<?php

class orderlist_controller extends cbase{
    //getname
    function getName(){return 'orderlist';}

    private $where;
    private $count;
    //获取订单列表
    //曹返
    //2015/1/13
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
        if(empty($shipper_staff)){
            $this -> xbase -> failed(99,'尚未填写个人信息');
        }
        //检测必要信息
        $orderStateIdList = $this ->getPosts('orderStateIdList');
        $sendAreaId = $this ->getPosts('sendAreaId');
        $receiveAreaId =$this ->getPosts('receiveAreaId');
        $requirePickupBeginDate= $this ->getPosts('requirePickupBeginDate');
        $requirePickupEndDate= $this -> getPosts('requirePickupEndDate');
        $submitOrderBeginDate = $this -> getPosts('submitOrderBeginDate');
        $submitOrderEndDate = $this -> getPosts('submitOrderEndDate');
        $orderId = $this -> getPosts('orderId');
        $postPerson = $this ->getPosts('postPerson');
        $submitOrderTimeSort = $this ->getPosts('submitOrderTimeSort');
        $requirePickupTimeSort = $this ->getPosts('requirePickupTimeSort');



        //页面处理
        $pg   = ($this -> getPosts('page'))?$this -> getPosts('page'):1;
        $size = ($this -> getPosts('pageSize'))?$this -> getPosts('pageSize'):15;


        //信息处理

        //单orderid match
        if(!empty($orderId)){
            $rc = $this ->shippersql -> get_order_by_id($orderId);
            if(empty($rc)){
                $this-> xbase -> failed(99,'找不到ID对应ORDER');
            }
            $rc2 = $this -> shippersql -> get_shipper_goods_list($orderId);
            if(empty($rc2)){
                $this-> xbase -> failed(99,'找不到匹配的项目');
            }
            if($rc[0]['shipperid']!=$shipper_staff['shipperid']){
                $this ->xbase ->failed(99,'订单所有者不匹配');
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
            $this -> shippersql -> memcache_save();
            $this -> xbase -> success($result);
        }

        //多条构成及match
        //初始化
        $this->where = '';
        $this->count = 0;

        $this->match_to_mysql($shipper_staff['shipperid'],'shipperid','default');

        if(!empty($orderStateIdList)){
            $this->match_to_mysql($orderStateIdList,'status','dearr');
        }

        $this->match_to_mysql($sendAreaId[0],'sender_provinceid','default');

        if(!empty($sendAreaId)){
            $sendAreaId = explode(',', $sendAreaId);
            $this->match_to_mysql($sendAreaId[0],'sender_provinceid','default');
            $this->match_to_mysql($sendAreaId[1],'sender_cityid','default');
            $this->match_to_mysql($sendAreaId[2],'sender_districtid','default');
        }

        if(!empty($receiveAreaId)){
            $receiveAreaId = explode(',', $receiveAreaId);
            $this->match_to_mysql($receiveAreaId[0],'receiver_provinceid','default');
            $this->match_to_mysql($receiveAreaId[1],'receiver_cityid','default');
            $this->match_to_mysql($receiveAreaId[2],'receiver_districtid','default');
        }

        if(!empty($requirePickupBeginDate)){
            $this ->match_to_mysql($requirePickupBeginDate,'sender_require_post_time','datea');
        }

        if(!empty($requirePickupEndDate)){
            $this ->match_to_mysql($requirePickupEndDate,'sender_require_post_time','dateb');
        }

        if(!empty($submitOrderBeginDate)){
            $this ->match_to_mysql($submitOrderBeginDate,'create_time','datea');
        }

        if(!empty($submitOrderEndDate)){
            $this ->match_to_mysql($submitOrderEndDate,'create_time','dateb');
        }

        if(!empty($postPerson)){
            $this ->match_to_mysql($postPerson,'sender_name','dateb');
        }

        if(!empty($submitOrderTimeSort)){
            $this ->match_to_mysql($submitOrderTimeSort,'create_time','orderby');
        }

        if(!empty($requirePickupTimeSort)){
            $this ->match_to_mysql($requirePickupTimeSort ,'receiver_require_arrive_time','orderby');
        }

        if(!empty($submitOrderTimeSort) && !empty($requirePickupTimeSort)){
            $this ->xbase->failed(99,'不能设置两个排序方式');
        }

        if(empty($submitOrderTimeSort) && empty($requirePickupTimeSort)){
            $this ->match_to_mysql($requirePickupTimeSort ,'create_time','orderby');
        }

        $rc = $this -> shippersql -> get_order_by_sql($this->where,$pg-1,$size);
        if(empty($rc)){
            $this-> xbase -> failed(99,'找不到匹配的项目');
        }

        //shipper_goods_list
        $result = array();

        foreach($rc as $k=>$v){
            $result[$k]['orderId'] = $v['id'];

            $rc2 = $this -> shippersql -> get_shipper_goods_list($v['id']);
            if(empty($rc2)){
                $this-> xbase -> failed(99,'找不到匹配的项目');
            }

            if($v['shipperid']==$shipper_staff['shipperid']){
                $result[$k]['isOwner'] =1;
            }else{
                $result[$k]['isOwner'] =0;
            }
            $result[$k]['orderStatusId'] = $v['status'];
            $result[$k]['orderStatus'] = $this->get_status($v['status']);
            if($result[$k]['isOwner'] ==1){
                $result[$k]['orderPric']=$v['payment'];
                $result[$k]['insuranceAmount'] = $v['insurance_amount'];
            }
            $result[$k]['sender'] = array();
            $result[$k]['sender']['company'] =$v['sender_company'];
            $result[$k]['sender']['name'] = $v['sender_name'];
            $result[$k]['sender']['mobile'] = $v['sender_mobile'];
            $result[$k]['sender']['areaIdList'] = $v['sender_provinceid'].','.$v['sender_cityid'].','.$v['sender_districtid'];
            $result[$k]['sender']['areaList'] = $v['sender_province'].','.$v['sender_city'].','.$v['sender_district'];
            $result[$k]['sender']['detailAddress'] = $v['sender_address'];

            $result[$k]['receiver'] = array();
            $result[$k]['receiver']['company'] = $v['receiver_company'];
            $result[$k]['receiver']['name'] = $v['receiver_name'];
            $result[$k]['receiver']['mobile'] = $v['receiver_mobile'];
            $result[$k]['receiver']['areaIdList'] = $v['receiver_provinceid'].','.$v['receiver_cityid'].','.$v['receiver_districtid'];
            $result[$k]['receiver']['areaList'] = $v['receiver_province'].','.$v['receiver_city'].','.$v['receiver_district'];
            $result[$k]['receiver']['detailAddress'] = $v['receiver_address'];

            $result[$k]['productList'] = $this -> dexarr($rc2 );
            $result[$k]['requirePickupTime'] = $v['sender_require_post_time'];
            $result[$k]['requireArriveTime'] = $v['receiver_require_arrive_time'];
            $result[$k]['isBuyInsurance'] = $v['is_buy_insurance'];
        }


        $this -> shippersql -> memcache_save();
        $this -> xbase -> success($result);
        //数据库操作
        //输出
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

    function match_to_mysql($v,$k,$kind){
        if($this->count != 0 && $kind!='orderby' ){
            $this->where = $this->where.' AND ';
        }
        $this ->count = $this->count+1;

        //反解数据到队列
        if($kind == 'dearr' ){
            $this->where = $this->where.' (';
            $v = explode(',', $v);
            $i = 0;
            foreach($v as $key =>$value){
                if($i !=0){$this->where = $this->where.' OR ';}
                $this->where = $this->where.$this ->match_add($k,$value);
                $i++;
            }
            $this->where = $this->where.')';
        }

        if($kind=='default'){
            $this->where = $this->where.$this ->match_add($k,$v);
        }

        if($kind=='datea'){
            $this->where = $this->where.$this ->match_add_a($k,$v);
        }

        if($kind=='dateb'){
            $this->where = $this->where.$this ->match_add_b($k,$v);
        }

        if($kind=='orderby'){
            $this->where = $this->where." ORDER BY `".$k."`";
        }
    }

    function match_add($k,$v){
        return " `".$k."`"."='".$v."' ";
    }

    function match_add_a($k,$v){
        return " `".$k."`".">'".$v."' ";
    }

    function match_add_b($k,$v){
        return " `".$k."`"."<'".$v."' ";
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