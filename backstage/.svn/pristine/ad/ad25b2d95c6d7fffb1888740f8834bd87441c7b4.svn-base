<?php

class reciver_controller extends cbase{
    //getname
    function getName(){return 'reciver';}

    //添加常用收货人
    //曹返
    //2015/1/11
    function addnew_action(){
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
        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        //检测必要信息

        $name =  $this -> xbase -> checkpost('name');
        $mobile =  $this -> xbase -> checkpost('mobile');
        $areaIdList = $this -> xbase -> checkpost('areaIdList');
        $areaList = $this -> xbase -> checkpost('$areaList');
        $detailAddress = $this -> xbase -> checkpost('detailAddress');
        $company = $this ->getPosts('company');

        $arr = array();
        $arr['shipperid']= $shipper_staff['shipperid'];
        $arr['type'] = 2;
        $arr['name'] = $name;
        $arr['mobile'] = $mobile;
        $arr['company'] = $company;
        $arr['address'] = $detailAddress;

        //地址联动
        $address = explode(',', $areaIdList);
        $add = explode(',', $areaList);
        $this -> xbase ->len3($add);
        $this -> xbase ->len3($address);

        $arr['provinceid'] = $address[0];
        $arr['cityid'] = $address[1];
        $arr['districtid'] = $address[2];
        $arr['province'] = $add[0];
        $arr['city'] = $add[1];
        $arr['district'] = $add[2];
        $arr['update_time'] = date('Y-m-d H:i:s');
        $arr['accountid'] = $userinfo['id'];

        //数据库操作
        $rc = $this -> shippersql -> set_shipper_contact($arr,$shipper_staff['shipperid']);
        //输出
        if(empty($rc)){
            $this-> xbase -> failed(99,'数据库异常，写入失败');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }


    //修改常用收货人
    //曹返
    //2015/1/11
    function change_action(){
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
        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        //检测必要信息

        $senderId = $this -> xbase -> checkpost('senderId');
        $name =  $this -> xbase -> checkpost('name');
        $mobile =  $this -> xbase -> checkpost('mobile');
        $areaIdList = $this -> xbase -> checkpost('areaIdList');
        $areaList = $this -> xbase -> checkpost('$areaList');
        $detailAddress = $this -> xbase -> checkpost('detailAddress');
        $company = $this -> xbase -> checkpost('company');

        $arr = array();
        $arr['type'] = 2;
        $arr['name'] = $name;
        $arr['mobile'] = $mobile;
        $arr['company'] = $company;
        $arr['address'] = $detailAddress;

        //地址联动
        $address = explode(',', $areaIdList);
        $add = explode(',', $areaList);
        $this -> xbase ->len3($add);
        $this -> xbase ->len3($address);

        $arr['provinceid'] = $address[0];
        $arr['cityid'] = $address[1];
        $arr['districtid'] = $address[2];
        $arr['province'] = $add[0];
        $arr['city'] = $add[1];
        $arr['district'] = $add[2];
        $arr['update_time'] = date('Y-m-d H:i:s');
        $arr['accountid'] = $userinfo['id'];

        //数据库操作

        $rc = $this -> shippersql -> update_shipper_contact($arr,$shipper_staff['shipperid'],$senderId);

        //输出
        if(empty($rc)){
            $this-> xbase -> failed(4,'常用收货人Id不存在');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }


    //删除常用收货人
    //曹返
    //2015/1/11
    function del_action(){
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
        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        //检测必要信息

        $senderId = $this -> xbase -> checkpost('senderId');
        //数据库操作

        $rc = $this -> shippersql -> del_shipper_contact($shipper_staff['shipperid'],$senderId);

        //输出
        if($rc==0){
            $this-> xbase -> failed(4,'常用收货人Id不存在');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }


    //获取常用收货人列表
    //曹返
    //2015/1/11
    function getlist_action(){
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
        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);

        //数据库操作
        $pg   = ($this -> getPosts('page'))?$this -> getPosts('page'):1;
        $size = ($this -> getPosts('pageSize'))?$this -> getPosts('pageSize'):15;

        $rc = $this -> shippersql -> get_shipper_contact($shipper_staff['shipperid'],2,$pg-1,$size);
        //输出
        if(empty($rc)){
            $this-> xbase -> failed(99,'尚未添加常用联系人');
        }

        //编码
        $result =array();
        foreach ($rc as $k=>$v){
            $result[$k]['senderId']=$v['id'];
            $result[$k]['name']=$v['name'];
            $result[$k]['mobile']=$v['mobile'];
            $result[$k]['areaIdList']=$v['provinceid'].','.$v['cityid'].','.$v['districtid'];
            $result[$k]['areaList']=$v['province'].','.$v['city'].','.$v['district'];
            $result[$k]['detailAddress']=$v['address'];
            $result[$k]['company']=$v['company'];
        }

        $this -> shippersql -> memcache_save();
        $this -> xbase -> success($result);
    }
}