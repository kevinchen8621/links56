<?php

class sysmessage_controller extends cbase{
    //getname
    function getName(){return 'sysmessage';}

    //获取未读消息
    //曹返
    //2015/1/11
    function get_action(){
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
        $this -> xbase -> check($userinfo,false);
        //sql初始化
        $this ->shippersql->set_global($token,$this -> ltoken ->getall());
        //信息处理
        $rc = $this -> shippersql -> get_message_unread($userinfo['id']);
        //数据库操作
        if(empty($rc)){
            $this-> xbase -> failed(99,'空的消息列表');
        }

        $result =array();
        foreach($rc as $k=>$v){
            $result[$k] =array();
            $result[$k]['messageId'] = $v['id'];
            $result[$k]['message'] = $v['message'];
            $result[$k]['data'] = $v['send_time'];
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success($result);
        //输出
    }


    //修改消息状态为已读
    //曹返
    //2015/1/11
    function read_action(){
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
        $this -> xbase -> check($userinfo,false);
        //sql初始化
        $this ->shippersql->set_global($token,$this -> ltoken ->getall());
        //信息处理
        $arr = $this ->xbase ->checkpost('messageIdList');
        $arr = explode(',', $arr);

        $len = count($arr);
        foreach($arr as $v){
            $rc = $this -> shippersql -> update_message_read($v);
            if(empty($rc)){
                $this -> xbase ->failed(99,'重复操作');
            }
        }
        $len = $userinfo['message_number']-$len;
        if($len < 0){
            //重建消息数索引
            $len = $this -> shippersql -> rebulid_message($userinfo['id']);
        }
        $rc2 = $this -> shippersql -> update_account_message($userinfo['id'],$len);


        //数据库操作
        if(empty($rc2)){
            $this-> xbase -> failed(99,'系统错误');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
        //输出
    }


    //发送消息
    //曹返
    //2015/1/11
    function send_action(){
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
        $this -> xbase -> check($userinfo,false);
        //sql初始化
        $this ->shippersql->set_global($token,$this -> ltoken ->getall());
        //信息处理
        $msg = $this ->xbase ->checkpost('message');
        $arr = array();
        $arr['accountid'] = $userinfo['id'];
        $arr['type'] = 1;
        $arr['source'] = 0;
        $arr['message'] = $msg;
        $arr['send_time'] = date('Y-m-d H:i:s');
        $arr['is_read'] = 0;
        $arr['is_handle'] = 0;

        $rc2 = $this -> shippersql -> set_new_message($arr);


        //数据库操作
        if(empty($rc2)){
            $this-> xbase -> failed(99,'系统错误');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }

    //系统反馈
    //曹返
    //2015/1/11
    function request_action(){
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
        $this -> xbase -> check($userinfo,false);
        //sql初始化
        $this ->shippersql->set_global($token,$this -> ltoken ->getall());
        //信息处理
        $msg = $this ->xbase ->checkpost('message');
        $arr = array();
        $arr['accountid'] = $userinfo['id'];
        $arr['type'] = 2;
        $arr['source'] = 0;
        $arr['message'] = $msg;
        $arr['send_time'] = date('Y-m-d H:i:s');
        $arr['is_read'] = 0;
        $arr['is_handle'] = 0;

        $rc2 = $this -> shippersql -> set_new_message($arr);


        //数据库操作
        if(empty($rc2)){
            $this-> xbase -> failed(99,'系统错误');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }

    //给客户端的监听端口发送消息
    //曹返
    //2015/1/11
    function checkout_action(){
        //检测 token
        //检测必要信息
        //信息处理
        //数据库操作
        //输出
    }


    //给苹果设备发送消息通知
    //曹返
    //2015/1/11
    function applepush_action(){
        //检测 token
        //检测必要信息
        //信息处理
        //数据库操作
        //输出
    }

}