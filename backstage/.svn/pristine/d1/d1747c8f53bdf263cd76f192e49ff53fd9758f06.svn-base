<?php

class ctrucker_controller extends cbase {

    function __construct() {
        parent::__construct();
        $this->initMemcache();
    }

    function getName() {
        return 'ctrucker';
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

    function getaccountinfo_action() { // 8.	获取账号详情
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $this->loadModule('links/mctrucker');
        $truckerinfo = $this->mctrucker->getTruckerByAccountid($accountid);

        $result = array();
        $result['mobile'] = $userinfo['mobile'];
        $result['registerDate'] = $userinfo['register_time'];
        if (empty($truckerinfo)) {
            $result['authenticateStatus'] = 0; // 认证状态（0未认证 1已认证 2 认证中 3认证失败）
            $result['hasTruckInfo'] = 0; // 车辆信息是否填写（0 未填写 1 已填写）
            $result['headUrl'] = ''; // 头像url
            $result['name'] = ''; // 姓名
            $result['star'] = 1; // 星级（1、2、3、4、5）
            $result['rank'] = '1';
            $result['currentMonthIncome'] = '0.00'; // 当月收入（元）
            $result['wayBillCount'] = 0; // 历史运单数（数字）
            $result['grabCount'] = 0; // 参与抢单数（数字）
        } else {
            $result['authenticateStatus'] = $truckerinfo['status']; // 认证状态（0未认证 1已认证 2 认证中 3认证失败）
            $result['hasTruckInfo'] = $truckerinfo['has_truck_info']; // 车辆信息是否填写（0 未填写 1 已填写）
            $result['headUrl'] = $truckerinfo['trucker_picurl']; // 头像url
            $result['name'] = $truckerinfo['name']; // 姓名
            $result['star'] = $truckerinfo['level']; // 星级（1、2、3、4、5）
            $result['rank'] = '1';
            $result['currentMonthIncome'] = $truckerinfo['month_income']; // 当月收入（元）
            $result['wayBillCount'] = $truckerinfo['total_waybill_number']; // 历史运单数（数字）
            $result['grabCount'] = $truckerinfo['biding_count']; // 参与抢单数（数字）
        }

        return $this->getRC('success', '', '', $result);
    }

    function requestauth_action() { // 9.	申请身份认证
        if ($this->isMissInput('name', 'idCardNumber', 'idCard', 'driverLicense', 'travelCardMainPage', 'travelCardSubPage', 'trailerTravelCard')) { // 检查入口参数
            return;
        }
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $name = $this->getPosts('name'); // 姓名：name
        $idcard = $this->getPosts('idCardNumber'); // 身份证号（15位或18位数字）：idCardNumber
        // 先检查有没有司机和货车信息，如果没有就返回错误
        $this->loadModule('links/mctrucker');
        $truckerinfo = $this->mctrucker->getTruckerByAccountid($accountid);
        if (empty($truckerinfo)) {
            return $this->getRC('fail', '99', '找不到该用户');
        }
        if (empty($truckerinfo['truckid'])) {
            return $this->getRC('fail', '98', '请先填写货车信息');
        }
        if ($truckerinfo['status'] == 1) { // 已认证就不用修改了
            return $this->getRC('fail', '97', '认证后不能再修改');
        }

        $this->loadLib('qcloud/Cos');
        $idcard_front_picurl = '' . $accountid . '_idcard_front_picurl.jpg'; // 身份证图片：idCard
        $driver_card_front_picurl = '' . $accountid . '_driver_card_front_picurl.jpg'; // 驾驶证图片：driverLicense
        $driving_license_front_picurl = '' . $accountid . '_driving_license_front_picurl.jpg'; // 行驶证（正页）图片：travelCardMainPage
        $driving_license_back_picurl = '' . $accountid . '_driving_license_back_picurl.jpg'; // 行驶证（副页）图片：travelCardSubPage
        $trailer_license_front_picurl = '' . $accountid . '_trailer_license_front_picurl.jpg'; // 挂车行驶证图片：trailerTravelCard
        $arr = array(
            $idcard_front_picurl => base64_decode($this->getPosts('idCard')),
            $driver_card_front_picurl => base64_decode($this->getPosts('driverLicense')),
            $driving_license_front_picurl => base64_decode($this->getPosts('travelCardMainPage')),
            $driving_license_back_picurl => base64_decode($this->getPosts('travelCardSubPage')),
            $trailer_license_front_picurl => base64_decode($this->getPosts('trailerTravelCard'))
        );
        $rc = $this->Cos->upload_x($arr);
        if (!$rc[$idcard_front_picurl] || !$rc[$driver_card_front_picurl] || !$rc[$driving_license_front_picurl] || !$rc[$driving_license_back_picurl] || !$rc[$trailer_license_front_picurl]) { // 如果成功就继续保存到数据库
            return $this->getRC('fail', '99', '上传失败');
        }


        // 更新司机姓名和认证状态
        $data = array();
        $data['name'] = $name;
        $data['status'] = 2;
        $this->mctrucker->update($data, 'accountid=' . $accountid, 'trucker');
        // 更新认证资料
        $truckerauthinfo = $this->mctrucker->getTruckerAuthByAccountid($accountid);
        if (empty($truckerauthinfo)) { // 新建
            $truckerauthinfo = array();
            $truckerauthinfo['accountid'] = $accountid;
            $truckerauthinfo['truckid'] = $truckerinfo['truckid'];
            $truckerauthinfo['name'] = $name;
            $truckerauthinfo['idcard'] = $idcard;
            $truckerauthinfo['idcard_front_picurl'] = $rc[$idcard_front_picurl];
            $truckerauthinfo['driver_card_front_picurl'] = $rc[$driver_card_front_picurl];
            $truckerauthinfo['driving_license_front_picurl'] = $rc[$driving_license_front_picurl];
            $truckerauthinfo['driving_license_back_picurl'] = $rc[$driving_license_back_picurl];
            $truckerauthinfo['trailer_license_front_picurl'] = $rc[$trailer_license_front_picurl];
            $this->mctrucker->insert($truckerauthinfo, 'trucker_auth');
        } else { // 更新
            unset($truckerauthinfo['accountid']);
            $truckerauthinfo['name'] = $name;
            $truckerauthinfo['idcard'] = $idcard;
            $truckerauthinfo['idcard_front_picurl'] = $rc[$idcard_front_picurl];
            $truckerauthinfo['driver_card_front_picurl'] = $rc[$driver_card_front_picurl];
            $truckerauthinfo['driving_license_front_picurl'] = $rc[$driving_license_front_picurl];
            $truckerauthinfo['driving_license_back_picurl'] = $rc[$driving_license_back_picurl];
            $truckerauthinfo['trailer_license_front_picurl'] = $rc[$trailer_license_front_picurl];
            $this->mctrucker->update($truckerauthinfo, 'accountid=' . $accountid, 'trucker_auth');
        }

        return $this->getRC('success');
    }

    function getauth_action() { // 10.	获取身份认证
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];
        $this->loadModule('links/mctrucker');
        $truckerauthinfo = $this->mctrucker->getTruckerAuthByAccountid($accountid);
        if (empty($truckerauthinfo)) { // 没有
            return $this->getRC('fail', '99', '没有认证信息');
        }
        $result = array();
        $result['headUrl'] = $truckerauthinfo['trucker_picurl']; // 头像url
        $result['name'] = $truckerauthinfo['name']; // 姓名
        $result['idCardNumber'] = $truckerauthinfo['idcard']; // 身份证号
        $result['idCardUrl'] = $truckerauthinfo['idcard_front_picurl']; // 身份证图片Url
        $result['driverLicenseUrl'] = $truckerauthinfo['driver_card_front_picurl']; // 驾驶证图片Url
        $result['travelCardMainPageUrl'] = $truckerauthinfo['driving_license_front_picurl']; // 行驶证（正页）图片Url
        $result['travelCardSubPageUrl'] = $truckerauthinfo['driving_license_back_picurl']; // 行驶证（副页）图片Url
        $result['trailerTravelCardUrl'] = $truckerauthinfo['trailer_license_front_picurl']; // 挂车行驶证图片Url

        return $this->getRC('success', '', '', $result);
    }

    function changehead_action() { // 11.	修改头像
        if ($this->isMissInput('head')) { // 检查入口参数
            return;
        }

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];


        $headurl = '' . $accountid . '_head.jpg';
        $this->loadLib('qcloud/Cos');
        $arr = array(
            $headurl => base64_decode($this->getPosts('head'))
        );
        $rc = $this->Cos->upload_x($arr);
        if (!$rc[$headurl]) { // 如果成功就继续保存到数据库
            return $this->getRC('fail', '99', '上传失败');
        }
        $this->loadModule('links/mctrucker');
        $this->mctrucker->updateHead($accountid, $rc[$headurl]);
        return $this->getRC('success');
    }

    function changetrucker_action() { // 12.	修改车辆信息
        if ($this->isMissInput('truckModel', 'truckNumber', 'truckLength', 'ton', 'truckBox', 'truckType', 'truckBrand', 'truckNumberPhoto', 'truckPhoto')) { // 检查入口参数
            return;
        }
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        // 获取输入内容
        $truckModel = $this->getPosts('truckModel'); // 车型
        $truckNumber = $this->getPosts('truckNumber'); // 车牌号
        $truckLength = $this->getPosts('truckLength'); // 车长
        $Ton = $this->getPosts('ton'); // 吨位
        $truckBox = $this->getPosts('truckBox'); // 货箱
        $truckType = $this->getPosts('truckType'); // 货车类型
        $truckBrand = $this->getPosts('truckBrand'); // 货车品牌
        $trucknumberurl = '' . $accountid . '_trucknumber.jpg'; // 车牌图片
        $truckphotourl = '' . $accountid . '_truckphoto.jpg'; // 货车照片图片
        //
        // 上传图片
        $this->loadLib('qcloud/Cos');
        $arr = array(
            $trucknumberurl => base64_decode($this->getPosts('truckNumberPhoto')),
            $truckphotourl => base64_decode($this->getPosts('truckPhoto'))
        );
        $rc = $this->Cos->upload_x($arr);
        if (!$rc[$trucknumberurl] || !$rc[$truckphotourl]) { // 如果成功就继续保存到数据库
            return $this->getRC('fail', '99', '上传失败');
        }

        // 更新认证资料
        $this->loadModule('links/mctrucker');
        $truckerinfo = $this->mctrucker->getTruckerByAccountid($accountid);

        if (empty($truckerinfo['truckid'])) { // 新建
            $truckinfo = array();
            $truckinfo['truckno'] = $truckNumber;
            $truckinfo['truck_typeid'] = $truckModel;
            $truckinfo['truck_lengthid'] = $truckLength;
            $truckinfo['truck_loadid'] = $Ton;
            $truckinfo['truck_bodyid'] = $truckBox;
            $truckinfo['truck_specialid'] = $truckType;
            $truckinfo['truck_brandid'] = $truckBrand;
            $truckinfo['is_duplicate'] = 0;
            $truckinfo['trucker_number'] = 1;
            $truckinfo['trucker_list'] = $accountid;
            $truckid = $this->mctrucker->insert($truckinfo, 'truck');

            if ($truckid < 0) {
                return $this->getRC('fail', '99', '系统维护中');
            }
            $this->mctrucker->update(array('truckid' => $truckid), 'accountid=' . $accountid, 'trucker'); // 更新司机的信息
            // 新建授权记录
            $truckerauthinfo = array();
            $truckerauthinfo['accountid'] = $accountid;
            $truckerauthinfo['truckid'] = $truckid;
            $truckerauthinfo['name'] = '';
            $truckerauthinfo['idcard'] = '';
            $truckerauthinfo['idcard_front_picurl'] = '';
            $truckerauthinfo['truck_front_picurl'] = $rc[$trucknumberurl];
            $truckerauthinfo['truck_picurl'] = $rc[$truckphotourl];
            $truckerauthinfo['driving_license_back_picurl'] = '';
            $truckerauthinfo['trailer_license_front_picurl'] = '';
            $truckerauthinfo['trucker_picurl'] = '';
            $this->mctrucker->insert($truckerauthinfo, 'trucker_auth');
        } else { // 更新
            $truckid = $truckerinfo['truckid'];
            $truckinfo = $this->mctrucker->getTruckByTruckid($truckid);
            unset($truckinfo['id']);
            $truckinfo['truckno'] = $truckNumber;
            $truckinfo['truck_typeid'] = $truckModel;
            $truckinfo['truck_lengthid'] = $truckLength;
            $truckinfo['truck_loadid'] = $Ton;
            $truckinfo['truck_bodyid'] = $truckBox;
            $truckinfo['truck_specialid'] = $truckType;
            $truckinfo['truck_brandid'] = $truckBrand;
            $truckinfo['is_duplicate'] = 0;
            $truckinfo['trucker_number'] = 1;
            if (!empty($truckinfo['trucker_list'])) {
                $trukers = explode(',', $truckinfo['trucker_list']);
                $isexist = false;
                foreach ($trukers as $t) {
                    if ($t == $accountid) {
                        $isexist = true;
                        break;
                    }
                }
                if (!$isexist) {
                    $trukers[] = $accountid;
                    $truckinfo['trucker_list'] = implode(',', $trukers);
                }
            } else {
                $truckinfo['trucker_list'] = $accountid;
            }
            $this->mctrucker->update($truckinfo, 'id=' . $truckid, 'truck');

            // 修改授权记录
            $truckerauthinfo = array();
            $truckerauthinfo['truck_front_picurl'] = $rc[$trucknumberurl];
            $truckerauthinfo['truck_picurl'] = $rc[$truckphotourl];
            $this->mctrucker->update($truckerauthinfo, 'accountid=' . $accountid, 'trucker_auth');
        }

        return $this->getRC('success');
    }

    function gettruck_action() { // 12.	获取车辆信息
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $this->loadModule('links/mctrucker');
        $truckerauthinfo = $this->mctrucker->getTruckerAuthByAccountid($accountid);
        if (empty($truckerauthinfo)) {
            return $this->getRC('fail', '98', '系统维护中');
        }
        $truckinfo = $this->mctrucker->getTruckByTruckid($truckerauthinfo['truckid']);
        if (empty($truckinfo)) {
            return $this->getRC('fail', '97', '系统维护中');
        }
        $result = array();
        $result['truckModel'] = $truckinfo['truck_typeid']; // 车型
        $result['truckNumber'] = $truckinfo['truckno']; // 车牌号
        $result['truckLength'] = $truckinfo['truck_lengthid']; // 车长
        $result['ton'] = $truckinfo['truck_loadid']; // 吨位
        $result['truckBox'] = $truckinfo['truck_bodyid']; // 货箱
        $result['truckType'] = $truckinfo['truck_specialid']; // 货车类型
        $result['truckBrand'] = $truckinfo['truck_brandid']; // 货车品牌
        $result['truckNumberPhotoUrl'] = $truckerauthinfo['truck_front_picurl']; // 车牌图片url
        $result['truckPhotoUrl'] = $truckerauthinfo['truck_picurl']; // 货车照片图片url


        return $this->getRC('success', '', '', $result);
    }

    function getbanks_action() { // 13.	获取银行卡列表
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $this->loadModule('links/mctrucker');
        $bankinfo = $this->mctrucker->getBanksByAccountid($accountid);
        $result = array();
        if (!empty($bankinfo)) { // 没有
            foreach ($bankinfo as $onebank) {
                $ob = array();
                $ob['bankCardId'] = $onebank['id']; // 银行卡ID，对应数据库的id
                $ob['bank'] = $onebank['bank']; // 开户行
                $ob['name'] = $onebank['name']; // 持卡人
                $ob['bankAreaIdList'] = $onebank['provinceid'] . ',' . $onebank['cityid']; // 开户行所在地区Id列表（逗号分隔）
                $ob['bankAreaList'] = $onebank['province'] . ',' . $onebank['city']; // 开户行所在地区列表（逗号分隔）
                $ob['bankCardNumber'] = $onebank['card_num']; // 银行卡号
                $ob['bankCardUrl'] = ''; // $onebank['']; // 银行卡Url
                $result[] = $ob;
            }
        }

        return $this->getRC('success', '', '', $result);
    }

    function updatebank_action() { // 14.	修改银行卡信息
        if ($this->isMissInput('bankCardId', 'name', 'bankAreaIdList', 'bankAreaList', 'bank', 'bankCardNumber')) { // 检查入口参数
            return;
        }
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }

        $bankid = $this->getPosts('bankCardId'); // 银行卡ID
        $name = $this->getPosts('name'); // 持卡人
        $bankAreaIdList = $this->getPosts('bankAreaIdList'); // 开户行所在地区Id列表（逗号分隔）
        $bankAreaList = $this->getPosts('bankAreaList'); // 开户行所在地区列表（逗号分隔）
        $bank = $this->getPosts('bank'); // 开户行
        $bankCardNumber = $this->getPosts('bankCardNumber'); // 银行卡号

        $bankAreaIds = explode(',', $bankAreaIdList);
        $bankAreas = explode(',', $bankAreaList);

        $bankinfo = array();
        $bankinfo['bank'] = $bank;
        $bankinfo['card_num'] = $bankCardNumber;
        $bankinfo['name'] = $name;
        $bankinfo['provinceid'] = $bankAreaIds[0];
        $bankinfo['cityid'] = $bankAreaIds[1];
        $bankinfo['province'] = $bankAreas[0];
        $bankinfo['city'] = $bankAreas[1];
        $bankinfo['update_time'] = date('Y-m-d H:i:s');
        $this->loadModule('links/mctrucker');
        $this->mctrucker->update($bankinfo, 'id=' . $bankid, 'trucker_bank_list');

        return $this->getRC('success');
    }

    function addbank_action() { // 15.	添加银行卡信息
        if ($this->isMissInput('name', 'bankAreaIdList', 'bankAreaList', 'bank', 'bankCardNumber')) { // 检查入口参数
            return;
        }
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $name = $this->getPosts('name'); // 持卡人
        $bankAreaIdList = $this->getPosts('bankAreaIdList'); // 开户行所在地区Id列表（逗号分隔）
        $bankAreaList = $this->getPosts('bankAreaList'); // 开户行所在地区列表（逗号分隔）
        $bank = $this->getPosts('bank'); // 开户行
        $bankCardNumber = $this->getPosts('bankCardNumber'); // 银行卡号

        $bankAreaIds = explode(',', $bankAreaIdList);
        $bankAreas = explode(',', $bankAreaList);

        $bankinfo = array();
        $bankinfo['accountid'] = $accountid;
        $bankinfo['bank'] = $bank;
        $bankinfo['card_num'] = $bankCardNumber;
        $bankinfo['name'] = $name;
        $bankinfo['provinceid'] = $bankAreaIds[0];
        $bankinfo['cityid'] = isset($bankAreaIds[1]) ? $bankAreaIds[1] : '';
        $bankinfo['province'] = $bankAreas[0];
        $bankinfo['city'] = isset($bankAreas[1]) ? $bankAreas[1] : '';
        $bankinfo['update_time'] = date('Y-m-d H:i:s');
        $this->loadModule('links/mctrucker');
        $this->mctrucker->insert($bankinfo, 'trucker_bank_list');

        return $this->getRC('success');
    }

    function delbank_action() { // 17.	删除银行卡信息
        if ($this->isMissInput('bankCardId')) { // 检查入口参数
            return;
        }
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }
        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        $bankid = $this->getPosts('bankCardId'); // 持卡人

        $this->loadModule('links/mctrucker');
        $this->mctrucker->delete('`id`=' . $bankid, 'trucker_bank_list');

        return $this->getRC('success');
    }

    function getrank_action() { // 16.	获取司机排名列表
        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token无效');
        }

        $userinfo = $this->ltoken->get('userinfo');
        $accountid = $userinfo['id'];

        // 先获取当月所有排行，缓冲没有就加载到上面
        $this->initMemcache();
        $allrank = $this->memcache->get('trucker_rank_' . date('Y-m'));

        if (true || empty($allrank)) {
            $this->loadModule('links/mctrucker');
            $allrank = $this->mctrucker->getList('`rank_date`="' . date('Y-m') . '" ORDER BY income DESC, update_time ASC', 0, 1000000, 'trucker_rank');
            $this->memcache->set('trucker_rank_' . date('Y-m'), $allrank);
        }

        // 先创建六个
        $ranks = array();
        $num = 0;
        $mekey = -1;
        $merank = -1;
        $maxrank = count($allrank);
        foreach ($allrank as $key => $truck) {
            if ($truck['truckerid'] == $accountid) {
                $mekey = $truck['truckerid'];
                $merank = $key;
            }

            if ($num < 6) {
                $onerank = array();
                $onerank['rank'] = $key + 1;
                $onerank['userId'] = $truck['truckerid'];
                $onerank['truckNumber'] = $truck['truckno'];
                $onerank['wayBillCount'] = $truck['income'];
                $ranks[$num] = $onerank;
                $num++;
            }
        }
        if ($merank < 0) { // 没记录
            $trukcerinfo = $this->mctrucker->getTruckerByAccountid($accountid);
            if (empty($trukcerinfo)) {
                return $this->getRC('fail', '99', '系统维护中');
            }
            $onerank = &$ranks[5];
            $onerank['rank'] = $maxrank;
            $onerank['userId'] = $accountid;
            $onerank['truckNumber'] = $trukcerinfo['truckno'];
            $onerank['wayBillCount'] = 0;
        } else if ($merank > 4) { // 其他地方
            if ($merank == 5) { // 在第六位
                if ($merank == $maxrank - 1) {
                    $ranks[3] = $ranks[4];
                    $ranks[4] = $ranks[5];
                    $onerank = &$ranks[5];
                    $onerank['rank'] = $merank;
                    $onerank['userId'] = $allrank[$merank]['truckerid'];
                    $onerank['truckNumber'] = $allrank[$merank]['truckno'];
                    $onerank['wayBillCount'] = $allrank[$merank]['income'];
                } else {
                    $ranks[3] = $ranks[4];
                    $onerank = &$ranks[4];
                    $onerank['rank'] = $merank;
                    $onerank['userId'] = $allrank[$merank]['truckerid'];
                    $onerank['truckNumber'] = $allrank[$merank]['truckno'];
                    $onerank['wayBillCount'] = $allrank[$merank]['income'];
                    $onerank = &$ranks[5];
                    $onerank['rank'] = $merank + 1;
                    $onerank['userId'] = $allrank[$merank + 1]['truckerid'];
                    $onerank['truckNumber'] = $allrank[$merank + 1]['truckno'];
                    $onerank['wayBillCount'] = $allrank[$merank + 1]['income'];
                }
            } else if ($merank == $maxrank - 1) { // 最后一位
                $onerank = &$ranks[3];
                $onerank['rank'] = $merank - 2;
                $onerank['userId'] = $allrank[$merank - 2]['truckerid'];
                $onerank['truckNumber'] = $allrank[$merank - 2]['truckno'];
                $onerank['wayBillCount'] = $allrank[$merank - 2]['income'];
                $onerank = &$ranks[4];
                $onerank['rank'] = $merank - 1;
                $onerank['userId'] = $allrank[$merank - 1]['truckerid'];
                $onerank['truckNumber'] = $allrank[$merank - 1]['truckno'];
                $onerank['wayBillCount'] = $allrank[$merank - 1]['income'];
                $onerank = &$ranks[5];
                $onerank['rank'] = $merank;
                $onerank['userId'] = $allrank[$merank]['truckerid'];
                $onerank['truckNumber'] = $allrank[$merank]['truckno'];
                $onerank['wayBillCount'] = $allrank[$merank]['income'];
            } else {
                $onerank = &$ranks[3];
                $onerank['rank'] = $merank - 1;
                $onerank['userId'] = $allrank[$merank - 1]['truckerid'];
                $onerank['truckNumber'] = $allrank[$merank - 1]['truckno'];
                $onerank['wayBillCount'] = $allrank[$merank - 1]['income'];
                $onerank = &$ranks[4];
                $onerank['rank'] = $merank;
                $onerank['userId'] = $allrank[$merank]['truckerid'];
                $onerank['truckNumber'] = $allrank[$merank]['truckno'];
                $onerank['wayBillCount'] = $allrank[$merank]['income'];
                $onerank = &$ranks[5];
                $onerank['rank'] = $merank + 1;
                $onerank['userId'] = $allrank[$merank + 1]['truckerid'];
                $onerank['truckNumber'] = $allrank[$merank + 1]['truckno'];
                $onerank['wayBillCount'] = $allrank[$merank + 1]['income'];
            }
        }
        $result = array();
        $result['month'] = date('m');
        $result['rankList'] = $ranks;
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
