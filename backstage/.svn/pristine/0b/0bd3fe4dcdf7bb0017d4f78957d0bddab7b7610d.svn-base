<?php

class cwaybill_controller extends cbase {

    function __construct() {
        parent::__construct();
        $this->initMemcache();
    }

    function getName() {
        return 'cwaybill';
    }

    function index_action() {
        echo 1;
        exit;
        $op = $this->getPosts('_act');
        if (empty($op)) {
            return;
        }
        $api = $op + '_action';
        $this->$api();
    }

    private function isMissInput() {
        $num = func_num_args();
        $list = func_get_args();
        for ($i = 0; $i < $num; $i++) {
            $name = $list[$i];
            $p = $this->getPosts($name);
            if (empty($p)) { // 已经加载过就不需要再加载了
                return $this->getRC('fail', '1', '请求参数缺失（' . $name . '）');
            }
        }
        return false;
    }

    function getbidinglist_action() { // 19.	获取当前抢单列表
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $page = $this->getPosts('page', 0); // 页码：page（可选）（不指定，默认1）
        $pageSize = $this->getPosts('pageSize', 15); // 每页条数：pageSize（可选）（不指定，默认15）

        $this->loadModule('links/mcwaybill');
        $waybillinfo = $this->mcwaybill->getList('1 ORDER BY `bidding_time` DESC', $page, $pageSize, 'bidding');

        $result = array();

        if (!empty($waybillinfo)) {
            foreach ($waybillinfo as $waybill) {
                $onewaybill = array();
                $onewaybill['grabBillId'] = $waybill['id']; // 抢单Id
                $onewaybill['isClosed'] = ($waybill['status'] > 1) ? 1 : 0; // 是否已经结束
                $onewaybill['startGrabTime'] = $waybill['bidding_time']; // 开抢时间
                $onewaybill['fromAreaList'] = $this->mcwaybill->combineArea($waybill['sender_province'], @$waybill['sender_city'], @$waybill['sender_district']); // 发货地区
                $onewaybill['toAreaList'] = $this->mcwaybill->combineArea($waybill['receiver_province'], @$waybill['receiver_city'], @$waybill['receiver_district']); // 收货地区
                $onewaybill['requirePickupTime'] = $waybill['require_sendtime']; // 要求提货时间
                $onewaybill['requireArriveTime'] = $waybill['require_arrivetime']; // 要求送达时间

                $goodslist = json_decode($waybill['goods_list'], true);
                if (count($goodslist) > 1) {
                    $onewaybill['productName'] = '多货品'; // 货品名称
                } else {
                    $onewaybill['productName'] = $goodslist[0]['name']; // 货品名称
                }
                $volume = 0;
                foreach ($goodslist as $gl) {
                    $volume += $gl['number'] * $gl['length'] * $gl['width'] * $gl['height'];
                }
                $onewaybill['productVolume'] = $volume; // 货品体积（单位：立方米）

                $this->loadModule('links/mcpublic');
                $onewaybill['requireTruckType'] = $this->mcpublic->getTruckLength($waybill['require_truck_length']); // 车型（单位：米）
                $onewaybill['requireTruckCount'] = $waybill['truck_number']; // 需车数量（单位：辆）
                $onewaybill['wayBillPrice'] = $waybill['freight']; // 每车运单价格（单位：元）

                $biddingrecord = $this->mcwaybill->getBiddingRecord($waybill['id'], $accountid);
                if (empty($biddingrecord)) {
                    $onewaybill['isJoinGrab'] = 0; // 是否参与抢单（1 已参与 0 未参与）
                    $onewaybill['grabResultStatus'] = 3; // 抢单结果状态(1 成功 2 失败 3 待确认)
                } else {
                    $onewaybill['isJoinGrab'] = 1; // 是否参与抢单（1 已参与 0 未参与）
                    $onewaybill['grabResultStatus'] = $biddingrecord['result_status']; // 抢单结果状态(1 成功 2 失败 3 待确认)
                }
                $result[] = $onewaybill;
            }
        }

        return $this->getRC('success', '', '', $result);
    }

    function getbidinginfo_action() { // 18.	获取抢单详情
        if ($this->isMissInput('grabBillId')) { // 检查入口参数
            return;
        }

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $grabBillId = $this->getPosts('grabBillId');
        $this->loadModule('links/mcwaybill', 'links/mcpublic');
        $biddinginfo = $this->mcwaybill->getBiddingById($grabBillId);
        if (empty($biddinginfo)) {
            return $this->getRC('fail', '98', '无效抢单');
        }
        $biddingrecord = $this->mcwaybill->getBiddingRecord($grabBillId, $accountid);

        $result = array();
        $result['grabBillId'] = $grabBillId; // 抢单Id
        $result['isClosed'] = ($biddinginfo['status'] > 1) ? 1 : 0; // 是否已经结束
        $result['startGrabTime'] = $biddinginfo['bidding_time']; // 开抢时间

        $result['requireTruckType'] = $this->mcpublic->getTruckLength($biddinginfo['require_truck_length']); // 车型（单位：米）
        $result['requireTruckCount'] = $biddinginfo['truck_number']; // 需车数量（单位：辆）
        $result['wayBillPrice'] = $biddinginfo['freight']; // 每车运单价格（单位：元）
        $result['requirePickupTime'] = $biddinginfo['require_sendtime']; // 要求提货时间
        $result['requireArriveTime'] = $biddinginfo['require_arrivetime']; // 要求送达时间
        $result['isBuyInsurance'] = $biddinginfo['is_buy_insurance']; // 是否购买保险
        $result['insuranceAmount'] = $biddinginfo['total_value']; // 要求保险金额（单位：元）

        if (true) { // 发货人
            $sender = array();
            $sender['company'] = $biddinginfo['sender_company'];
            $sender['name'] = $biddinginfo['sender_name'];
            $sender['mobile'] = $biddinginfo['sender_mobile'];
            $sender['detailAddress'] = $biddinginfo['sender_address'];
            $sender['areaIdList'] = $this->mcwaybill->combineArea($biddinginfo['sender_provinceid'], @$biddinginfo['sender_cityid'], @$biddinginfo['sender_districtid']);
            $sender['areaList'] = $this->mcwaybill->combineArea($biddinginfo['sender_province'], @$biddinginfo['sender_city'], @$biddinginfo['sender_district']);
            $result['sender'] = $sender; // 发货人
        }
        if (true) { // 收货人
            $receiver = array();
            $receiver['company'] = $biddinginfo['receiver_company'];
            $receiver['name'] = $biddinginfo['receiver_name'];
            $receiver['mobile'] = $biddinginfo['receiver_mobile'];
            $receiver['detailAddress'] = $biddinginfo['receiver_address'];
            $receiver['areaIdList'] = $this->mcwaybill->combineArea($biddinginfo['receiver_provinceid'], @$biddinginfo['receiver_cityid'], @$biddinginfo['receiver_districtid']);
            $receiver['areaList'] = $this->mcwaybill->combineArea($biddinginfo['receiver_province'], @$biddinginfo['receiver_city'], @$biddinginfo['receiver_district']);
            $result['receiver'] = $receiver; // 收货人
        }

        if (true) { // 货品列表
            $goodslist = json_decode($biddinginfo['goods_list'], true);
            $products = array();
            foreach ($goodslist as $gl) {
                $p = array();
                $p['productName'] = $gl['name'];
                $p['count'] = $gl['number'];
                $p['unitWeight'] = $gl['weight'];
                $p['unitLength'] = $gl['length'];
                $p['unitWidth'] = $gl['width'];
                $p['unitHeight'] = $gl['height'];
                $products[] = $p;
            }
            $result['productList'] = $products; // 货品列表
        }

        if (empty($biddingrecord)) { // 没有参与
            $result['isRemindMe'] = 0; // 抢单开始是否提醒我（1 提醒 0 不提醒）
            $onewaybill['isJoinGrab'] = 0; // 是否参与抢单（1 已参与 0 未参与）
            $onewaybill['grabResultStatus'] = 3; // 抢单结果状态(1 成功 2 失败 3 待确认)
            $result['joinGrabTime'] = ''; // 参与抢单时间
            $result['grabSuccessTime'] = ''; // 抢单成功时间
            $result['grabFailTime'] = ''; // 抢单失败时间
        } else {
            $result['isRemindMe'] = $biddingrecord['is_remind']; // 抢单开始是否提醒我（1 提醒 0 不提醒）
            $onewaybill['isJoinGrab'] = 1; // 是否参与抢单（1 已参与 0 未参与）
            $onewaybill['grabResultStatus'] = $biddingrecord['result_status']; // 抢单结果状态(1 成功 2 失败 3 待确认)
            $result['joinGrabTime'] = $biddingrecord['bidding_time']; // 参与抢单时间
            $result['grabSuccessTime'] = $biddingrecord['result_time']; // 抢单成功时间
            $result['grabFailTime'] = $biddingrecord['result_time']; // 抢单失败时间
        }

        // 所有成功抢单列表
        $biddingallrecord = $this->mcwaybill->getSuccessBiddingAllRecord($grabBillId);
        if (empty($biddingallrecord)) {
            $result['grabPersonCount'] = 0;
            $result['truckList'] = array();
        } else {
            $result['grabPersonCount'] = count($biddingallrecord);
            $truckers = array();
            foreach ($biddingallrecord as $bar) {
                $t = array();
                $t['userId'] = $bar['truckerid']; // 用户Id
                $t['truckNumber'] = $bar['truckno']; // 车牌号
                $t['star'] = $this->mcwaybill->getTruckerLevel($bar['truckerid']); // 星级（1、2、3、4、5）
                $t['grabSuccessTime'] = $bar['result_time']; // 抢单成功时间（该值为派车成功时间）
                $truckers[] = $t;
            }
            $result['truckList'] = $truckers;
        }


        return $this->getRC('success', '', '', $result);
    }

    function submitbiding_action() { // 19.	提交抢单
        if ($this->isMissInput('grabBillId')) { // 检查入口参数
            return;
        }

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $grabBillId = $this->getPosts('grabBillId');


        $this->loadModule('links/mcwaybill', 'links/mctrucker');
        $trucker = $this->mctrucker->getTruckerByAccountid($accountid);
        if (empty($trucker)) {
            return $this->getRC('fail', '7', '车辆信息未填写，请先填写');
        }
        if ($trucker['status'] == 0) { // 0未认证，1已认证，2待认证，3认证未通过
            return $this->getRC('fail', '4', '资料认证申请尚未提交，请先提交');
        }
        if ($trucker['status'] == 2) {
            return $this->getRC('fail', '5', '资料认证进行中，请耐心等待');
        }
        if ($trucker['status'] == 3) {
            return $this->getRC('fail', '6', '资料认证失败，请重新提交');
        }

        $truck = $this->mctrucker->getTruckByTruckid($trucker['truckid']);
        if (empty($truck)) {
            return $this->getRC('fail', '7', '车辆信息未填写，请先填写');
        }

        $biddinginfo = $this->mcwaybill->getBiddingById($grabBillId);
        if (empty($biddinginfo)) {
            return $this->getRC('fail', '98', '无效抢单');
        }
        if ($biddinginfo['status'] == 0) {
            return $this->getRC('fail', '8', '抢单还未开始');
        }
        if ($biddinginfo['status'] > 1) {
            return $this->getRC('fail', '9', '抢单已结束');
        }

        $truckerbiddinginfo_temp = $this->mcwaybill->getBiddingRecord($grabBillId, $accountid);
        if (!empty($truckerbiddinginfo_temp)) {
            return $this->getRC('fail', '10', '您已经参与过该抢单，不可以重复参与');
        }

        $truckerbiddinginfo = array();
        $truckerbiddinginfo['orderid'] = $biddinginfo['orderid']; // 订单对应id
        $truckerbiddinginfo['biddingid'] = $biddinginfo['id']; // 抢单对应id
        $truckerbiddinginfo['waybillid'] = ''; // 运单号id
        $truckerbiddinginfo['bidding_time'] = date('Y-m-d H:i:s'); // 抢单时间
        $truckerbiddinginfo['truckerid'] = $trucker['accountid']; // 司机id
        $truckerbiddinginfo['trucker_name'] = $trucker['truckno']; // 司机名字
        $truckerbiddinginfo['trucker_mobile'] = $userinfo['mobile']; // 司机电话
        $truckerbiddinginfo['truckid'] = $trucker['truckno']; // 卡车id
        $truckerbiddinginfo['truckno'] = $trucker['truckno']; // 车牌号
        $truckerbiddinginfo['is_remind'] = 0; // 抢单开始是否提醒我（1 提醒 0 不提醒）
        $truckerbiddinginfo['result_status'] = 3; // 抢单结果状态：1成功，2失败，3待确认，4异常关闭

        $rc = $this->mcwaybill->insert($truckerbiddinginfo, 'bidding_record');
        if ($rc < 0) {
            return $this->getRC('fail', '99', '系统维护中');
        }

        $updatebiddinginfo = array();
        $updatebiddinginfo['trucker_number'] = $biddinginfo['trucker_number'] + 1;
        $this->mcwaybill->update($updatebiddinginfo, '`id`=' . $biddinginfo['id'], 'bidding');


        return $this->getRC('success');
    }

    function setRemindMe_action() { // 21.	抢单开始提醒我
        if ($this->isMissInput('grabBillId', 'isRemindMe')) { // 检查入口参数
            return;
        }

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $grabBillId = $this->getPosts('grabBillId');
        $isRemindMe = $this->getPosts('isRemindMe');


        $this->loadModule('links/mcwaybill', 'links/mctrucker');
        $trucker = $this->mctrucker->getTruckerByAccountid($accountid);
        if (empty($trucker)) {
            return $this->getRC('fail', '7', '车辆信息未填写，请先填写');
        }
        if ($trucker['status'] == 0) { // 0未认证，1已认证，2待认证，3认证未通过
            return $this->getRC('fail', '4', '资料认证申请尚未提交，请先提交');
        }
        if ($trucker['status'] == 2) {
            return $this->getRC('fail', '5', '资料认证进行中，请耐心等待');
        }
        if ($trucker['status'] == 3) {
            return $this->getRC('fail', '6', '资料认证失败，请重新提交');
        }

        $truck = $this->mctrucker->getTruckByTruckid($trucker['truckid']);
        if (empty($truck)) {
            return $this->getRC('fail', '7', '车辆信息未填写，请先填写');
        }

        $biddinginfo = $this->mcwaybill->getBiddingById($grabBillId);
        if (empty($biddinginfo)) {
            return $this->getRC('fail', '98', '无效抢单');
        }
        if ($biddinginfo['status'] == 1) {
            return $this->getRC('fail', '8', '抢单已开始');
        }
        if ($biddinginfo['status'] > 1) {
            return $this->getRC('fail', '9', '抢单已结束');
        }

        $truckerbiddinginfo = $this->mcwaybill->getBiddingRecord($grabBillId, $accountid);
        if (empty($truckerbiddinginfo)) {
            return $this->getRC('fail', '10', '还没参与抢单');
        }

        $updatebiddinginfo = array();
        $updatebiddinginfo['is_remind'] = $isRemindMe;
        $rc = $this->mcwaybill->update($updatebiddinginfo, '`id`=' . $truckerbiddinginfo['id'], 'bidding_record');


        return $this->getRC('success');
    }

    function getMyBidding_action() { // 22.	获取我参与的抢单列表（按参与抢单时间降序排序）
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $page = $this->getPosts('page', 0);
        $pageSize = $this->getPosts('pageSize', 15);

        $result = array();
        $this->loadModule('links/mcwaybill', 'links/mctrucker', 'links/mcpublic');
        $mybiddding = $this->mcwaybill->getList('`truckerid` == ' . $accountid . ' ORDER BY `bidding_time` DESC ', $page, $pageSize, 'bidding_record');
        if (!empty($mybiddding) && is_array($mybiddding)) {
            foreach ($mybiddding as $mb) {
                $biddinginfo = $this->mcwaybill->getBiddingById($mb['biddingid']);
                if (empty($biddinginfo)) {
                    return $this->getRC('fail', '98', '抢单信息不存在[' . $mb['biddingid'] . ']');
                }
                $mymb = array();
                $mymb['grabBillId'] = $mb['biddingid']; // 抢单Id
                $mymb['grabTime'] = $mb['bidding_time']; // 抢单时间
                $mymb['fromAreaList'] = $this->mcwaybill->combineArea($biddinginfo['sender_province'], @$biddinginfo['sender_city'], @$biddinginfo['sender_district']); // 发货地区
                $mymb['toAreaList'] = $this->mcwaybill->combineArea($biddinginfo['receiver_province'], @$biddinginfo['receiver_city'], @$biddinginfo['receiver_district']); // 收货地区
                $mymb['requirePickupTime'] = $biddinginfo['require_sendtime']; // 要求提货时间
                $mymb['requireArriveTime'] = $biddinginfo['require_arrivetime']; // 要求送达时间

                if (true) {
                    $goodslist = json_decode($waybill['goods_list'], true);
                    if (count($goodslist) > 1) {
                        $mymb['productName'] = '多货品'; // 货品名称
                    } else {
                        $mymb['productName'] = $goodslist[0]['name']; // 货品名称
                    }
                    $volume = 0;
                    foreach ($goodslist as $gl) {
                        $volume += $gl['number'] * $gl['length'] * $gl['width'] * $gl['height'];
                    }
                    $mymb['productVolume'] = $volume; // 货品体积（单位：立方米）
                }
                $mymb['requireTruckType'] = $this->mcpublic->getTruckLength($biddinginfo['require_truck_length']); // 车型（单位：米）
                $mymb['requireTruckCount'] = $biddinginfo['truck_number']; // 需车数量（单位：辆）
                $mymb['wayBillPrice'] = $biddinginfo['freight']; // 每车运单价格（单位：元）
                $mymb['grabResultStatus'] = $mb['result_status']; // 抢单结果状态(1 成功 2 失败 3 待确认)

                $result[] = $mymb;
            }
        }

        return $this->getRC('success', '', '', $result);
    }

    function cb_action() {
        // hlog::logDebug('faace7', var_export($_GET, true));
    }

    function getRC($status, $failcode = '', $failmessage = '', $result = '') {
        $rc = array();
        $rc['status'] = $status;
        if (!empty($failcode)) {
            $rc['failCode'] = $failcode;
            $rc['failMessage'] = $failmessage;
        } else if (!empty($result)) {
            $rc['result'] = $result;
        }
        echo preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($rc));
        return true;
    }

    function getToken() {
        return $this->getServers('HTTP_TOKEN');
    }

    private function getRandCode($length, $onlynum = false) {
        $basecode = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rc = '';
        for ($j = 0; $j < $length; $j++) {
            $i = $onlynum ? rand(0, 9) : rand(0, 61);
            $rc .= substr($basecode, $i, 1);
        }
        return $rc;
    }

}
