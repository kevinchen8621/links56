<?php

class company_controller extends cbase{
    //getname
    function getName(){return 'company';}

    //修改公司信息
    //曹返
    //2015/1/11
    function cpinfochange_action(){
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
        if(empty($shipper_staff)){
            $this -> xbase -> failed(99,'尚未填写个人信息');
        }
        //shipper
        $shipper = $this -> shippersql -> get_shipper($shipper_staff['shipperid']);

        //信息处理
        $companyAddress = $this -> xbase -> checkpost('companyAddress');
        $phone = $this -> xbase -> checkpost('phone');
        $business = $this -> xbase -> checkpost('business');
        $yearTurnover = $this -> xbase -> checkpost('yearTurnover');
        $website = $this -> getPosts('website');
        $company = $this -> getPosts('company');

        $arr = array();
        $arr['address'] = $companyAddress;
        $arr['tel'] = $phone;
        $arr['business'] = $business;
        $arr['year_turnover'] = $yearTurnover;
        if(!empty($website)){
            $arr['website'] = $website;
        }
        if(!empty($company)){
            $arr['company'] = $company;
        }

        if(empty($shipper)){
            //新建
            $arr['shipperid'] = $shipper_staff['shipperid'];
            $arr['create_accountit'] = $userinfo['id'];
            if(empty($company)){
                $this -> xbase -> failed(99,'新建记录时，必须填写公司名');
            }

            $rc = $this -> shippersql -> set_shipper($arr);
        }else{
            //更新
            if($shipper['status']==1 && !empty($company)){
                $this -> xbase -> failed(99,'公司信息已认证，不可修改公司名');
            }

            if($shipper['status']==2 && !empty($company)){
                $this-> xbase -> failed(99,'公司信息认证中，不可修改公司名');
            }
            $rc = $this -> shippersql -> update_shipper($arr,$shipper['shipperid']);
        }

        //输出
        if($rc==0){
            $this-> xbase -> failed(99,'数据库异常，写入失败');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }

    //修改个人信息
    //曹返
    //2015/1/11
    function spinfochange_action(){
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

        $name = $this -> xbase -> checkpost('name');
        $sex = $this -> xbase -> checkpost('sex');
        $department = $this -> xbase -> checkpost('department');
        $position = $this -> xbase -> checkpost('position');

        $arr = array();

        $arr['name'] = $name;
        $arr['sex'] = $sex;
        $arr['department'] = $department;
        $arr['position'] = $position;

        //数据库操作
        $pd2 = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        if(empty($pd2)){
            //新数据
            $arr['accountid'] = $userinfo['id'];
            $rc = $this -> shippersql -> set_shipper_staff($arr);
        }else{
            //更新
            $rc = $this -> shippersql -> update_shipper_staff($arr,$userinfo['id']);
        }
        //输出
        if($rc==0){
            $this-> xbase -> failed(99,'数据库异常，写入失败');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }

    //申请认证
    //曹返
    //2015/1/11
    function gettoken_action(){
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
        $pic1 = $this -> xbase -> checkpost('businessLicense');
        $pic2 = $this -> xbase -> checkpost('organizationCertification');
        $pic3 = $this -> xbase -> checkpost('taxCertification');

        $this->loadLib('qcloud/Cos');
        $cos_obj = $this->Cos;

        $arr=array(
            'businessLicense.jpg'=>base64_decode($pic1),
            'organizationCertification.jpg'=>base64_decode($pic2),
            'taxCertification.jpg'=>base64_decode($pic3)
        );

        $rc = $cos_obj->upload_x($arr);
        foreach ($rc as $k=>$v) {
            if($v==false){
                $this -> xbase -> failed(99,'图片'.$k.'上传失败');
            }
        }

        $arr2 = array();
        $arr2['business_license_picurl'] = $rc['businessLicense.jpg'];
        $arr2['orcc_picurl'] = $rc['organizationCertification.jpg'];
        $arr2['tax_registration_picurl'] = $rc['taxCertification.jpg'];
        $arr2['request_accountid'] = $userinfo['id'];
        $arr2['apply_time'] = date('Y-m-d H:i:s');

        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        if(empty($shipper_staff)){
            $this -> xbase -> failed(99,'尚未填写个人信息');
        }

        //数据库操作
        $shipper_auth = $this -> shippersql -> get_shipper_auth($shipper_staff['shipperid']);
        if(empty($shipper_auth)){
            $arr2['shipperid']=$shipper_staff['shipperid'];
            $rc = $this -> shippersql -> set_shipper_auth($arr2);
        }else{
            //更新
            $rc = $this -> shippersql -> update_shipper_auth($arr2,$shipper_staff['shipperid']);
        }
        //输出
        if($rc==0){
            $this-> xbase -> failed(99,'数据库异常，写入失败');
        }
        $this -> shippersql -> memcache_save();
        $this -> xbase -> success();
    }
}