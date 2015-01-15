<?php

class account_controller extends cbase{
    //getname
    function getName(){return 'account';}

    //获取账号详情
    //曹返
    //2015/1/12
    function getinfo_action(){
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

        //shipper_staff
        $shipper_staff = $this -> shippersql -> get_shipper_staff($userinfo['id']);
        if(empty($shipper_staff)){
//            $this -> xbase -> failed(99,'尚未填写个人信息');
        }else{
            //shipper
            $shipper = $this -> shippersql -> get_shipper($shipper_staff['shipperid']);
            if(empty($shipper)){
                $shipper['status']=0;
//                $this -> xbase -> failed(99,'尚未填写企业信息');
            }
        }
        $this -> shippersql -> memcache_save();

        //信息处理
        $result = array();

        $result['mobile'] = $userinfo['mobile'];
        $result['passwordStrong'] =(!empty($userinfo['pwstrong']))?$userinfo['pwstrong']:'';
        $result['registerDate'] = $userinfo['register_time'];
        $result['company']=(empty($shipper))?$shipper['company']:'';
        $result['authenticateStatus']=(empty($shipper))?$shipper['status']:'';
        $result['companyAddress']=(empty($shipper))?$shipper['address']:'';
        $result['phone']=(empty($shipper))?$shipper['tel']:'';
        $result['website']=(empty($shipper))?$shipper['website']:'';
        $result['business']=(empty($shipper))?$shipper['business']:'';
        $result['yearTurnover']=(empty($shipper))?$shipper['year_turnover']:'';
        $result['name']=(empty($shipper_staff))?$shipper_staff['name']:'';
        $result['sex']=(empty($shipper_staff))?$shipper_staff['sex']:'';
        $result['department']=(empty($shipper_staff))?$shipper_staff['department']:'';
        $result['position']=(empty($shipper_staff))?$shipper_staff['position']:'';
        //输出
        $this->xbase->success($result);
    }
}