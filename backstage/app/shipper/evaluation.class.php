<?php

class evaluation_controller extends cbase{
    //getname
    function getName(){return 'evaluation';}

    //评价司机
    //曹返
    //2015/1/11
    function index_action(){
        //检测 token
        $this->loadLib('shipper/xbase');
        $this -> loadLib('links/ltoken');
        $this -> loadModule('links/shippersql');
        $token = $this->xbase->header_token();
        //token参数缺失
        if($token==''){
            $this -> xbase -> failed(1,'请求参数缺失(token)');
        };
        //token check
        $pd = $this -> ltoken -> token_decode($token);
        if($pd){
            $userinfo = $this -> ltoken -> get('userinfo');
        }else{
            //error token
            $this -> xbase -> failed(3,'token无效');
        }
        //检测货主身份及黑名单
        $this -> xbase -> check($userinfo);
        //sql初始化
        $this ->shippersql->set_global($token,$this -> ltoken ->getall());
        //检测必要信息
        $orderId = $this -> xbase -> checkpost('orderId');
        $driverId = $this -> xbase -> checkpost('driverId');
        $evaluate = $this -> xbase -> checkpost('evaluate');
        if($evaluate==2){
            $badReason = $this -> xbase -> checkpost('badReason');
        }
        if($badReason && $badReason==4){
            $otherBadReason = $this ->xbase -> checkpost('otherBadReason');
        }

        //信息处理
        $arr = array();
        $arr['rating'] = $evaluate;
        if($badReason && $badReason==1){
            $arr['rating_note'] = '服务态度不好';
        }
        if($badReason && $badReason==2){
            $arr['rating_note'] = '提货晚点';
        }
        if($badReason && $badReason==3){
            $arr['rating_note'] = '到达晚点';
        }
        if($badReason && $badReason==4){
            $arr['rating_note'] = $otherBadReason;
        }
        if($orderId!=$userinfo['id']){
            $this -> xbase ->failed(5,'该订单Id不属于该用户Id');
        }
        //数据库操作
        $rc = $this -> shippersql -> update_waybill_eva($arr,$driverId,$orderId);
        //输出
        if($rc==0){
            $this-> xbase -> failed(99,'找不到对应的运单');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }
}