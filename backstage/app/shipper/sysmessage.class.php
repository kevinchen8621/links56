<?php

class sysmessage_controller extends cbase
{
    //getname
    function getName(){return 'sysmessage';}

    //系统提醒设置
    //曹返
    //2015/1/11
    function setconfig_action(){
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
        $acceptOrder = $this->xbase->checkpost('acceptOrder');
        $waitDriver = $this->xbase->checkpost('waitDriver');
        $arriveNoPay = $this->xbase->checkpost('arriveNoPay');
        $paySuccess = $this->xbase->checkpost('paySuccess');
        $orderFinish = $this->xbase->checkpost('orderFinish');
        $orderClose = $this->xbase->checkpost('orderClose');

        //信息处理
        $arr = $acceptOrder . ',' . $waitDriver . ',' . $arriveNoPay . ',' . $paySuccess . ',' . $orderFinish . ',' . $orderClose;

        //数据库操作
        $arr =array('notice_list'=>$arr);
        $rc = $this->shippersql->update_shipper_staff_notice_list($arr,$userinfo['id']);
        //输出
        if (empty($rc)) {
            $this->xbase->failed(99, '系统错误');
        }
        $this->shippersql->memcache_save();
        $this->xbase->success();
    }


    //系统提醒设置获取
    //曹返
    //2015/1/11
    function getconfig_action(){
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
        //数据库操作
        $rc = $this->shippersql->get_shipper_staff_notice_list($userinfo['id']);
        //输出
        if (empty($rc)) {
            $arr = '1,1,1,1,1,1';
            $rc2 = $this->shippersql->update_shipper_staff_notice_list($arr,$userinfo['id']);
            $rc['notice_list']='1,1,1,1,1,1';
        }
        $arr = $rc['notice_list'];
        $arr = explode(',', $arr);
        $result =array();
        $result['acceptOrder']=$arr[0];
        $result['waitDriver']=$arr[1];
        $result['arriveNoPay']=$arr[2];
        $result['paySuccess']=$arr[3];
        $result['orderFinish']=$arr[4];
        $result['orderClose']=$arr[5];

        $this->shippersql->memcache_save();
        $this->xbase->success($result);
    }
}