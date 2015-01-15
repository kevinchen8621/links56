<?php

class cuserinfo_controller extends cbase {

    function __construct() {
        parent::__construct();
        $this->initMemcache();
    }

    function getName() {
        return 'cuserinfo';
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

    function signin_action() { // 普通登录
        if ($this->isMissInput('mobile', 'password')) { // 检查入口参数
            return;
        }

        $mobile = $this->getPosts('mobile');
        $pw = $this->getPosts('password');

        // 判断手机号码是否注册了，并且密码是否一样
        $this->loadModule('links/mcuserinfo');
        $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
        if (empty($userinfo)) {
            return $this->getRC('fail', '3', '手机号码不存在');
        }
        if ($userinfo['pw'] != $pw) {
            return $this->getRC('fail', '4', '密码错误');
        }
        // 登录了，并保存token，把account信息加载到内存
        $this->loadLib('links/ltoken');
        $token = $this->ltoken->token_encode($mobile, false); // 以手机方式保存token索引
        $this->ltoken->set('userinfo', $userinfo);
        $this->ltoken->save();

        // 返回对应的token值
        $result = array();
        $result['token'] = $this->ltoken->getToken();
        return $this->getRC('success', '', '', $result);
    }

    function actsignin_action() { // 动态密码登录
        if ($this->isMissInput('mobile', 'dynamicPassword')) { // 检查入口参数
            return;
        }

        $mobile = $this->getPosts('mobile');
        $pw = $this->getPosts('dynamicPassword');

        // 判断手机号码是否注册了
        $this->loadModule('links/mcuserinfo');
        $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
        if (empty($userinfo)) {
            return $this->getRC('fail', '3', '手机号码不存在');
        }

        $this->loadLib('links/ltoken');
        $this->ltoken->token_encode($mobile, false); // 以手机方式保存token索引
        $this->ltoken->set('userinfo', $userinfo);
        $sms1 = $this->ltoken->get('sms1');

        // 判断动态密码是否正确
        if ($sms1['code'] != $pw) {
            return $this->getRC('fail', '4', '动态密码错误');
        }
        // faacet $this->ltoken->del('sms1');
        $this->ltoken->save();

        $result = array();
        $result['token'] = $this->ltoken->getToken();
        return $this->getRC('success', '', '', $result);
    }

    function register_action() { // 4.	注册
        if ($this->isMissInput('mobile', 'password', 'verifyCode', 'role')) { // 检查入口参数
            return;
        }

        $mobile = $this->getPosts('mobile');
        $pw = $this->getPosts('password');
        $code = $this->getPosts('verifyCode');
        $passwordStrong = $this->getPosts('passwordStrong'); // 1 弱 2 中 3 强
        $role = $this->getPosts('role'); // 1运营，2财务，3主管，6货主（操作员），8卡车司机，9管理员
        $company = $this->getPosts('company');

        // 判断手机号码是否注册了
        $this->loadModule('links/mcuserinfo');
        $userinfo_temp = $this->mcuserinfo->getUserInfoByMobile($mobile);
        if (!empty($userinfo_temp)) {
            return $this->getRC('fail', '3', '手机号码已被人使用');
        }

        $this->loadLib('links/ltoken');
        $this->ltoken->token_encode($mobile, false); // 以手机方式保存token索引
        $sms2 = $this->ltoken->get('sms2');
        // 判断动态密码是否正确
        if ($sms2['code'] != $code) {
            return $this->getRC('fail', '4', '动态密码错误');
        }
        // faacet $this->ltoken->del('sms2');
        $this->ltoken->save();
        
        // 创建帐号
        $insertdata = array();
        $insertdata['mobile'] = $mobile;
        $insertdata['pw'] = $pw;
        if (!empty($passwordStrong)) {
            $insertdata['pwstrong'] = $passwordStrong;
        }
        $insertdata['role'] = $role;

        $insertdata['status'] = 1;
        $insertdata['last_signin_time'] = date('Y-m-d H:i:s');
        $insertdata['current_signin_time'] = date('Y-m-d H:i:s');
        $insertdata['register_time'] = date('Y-m-d H:i:s');
        $insertdata['message_number'] = 0;
        if (!empty($company)) {
            $insertdata['note'] = $company;
        }
//        $this->logit(json_encode($_POST), 'faace.txt');
//        $this->logit(json_encode($insertdata), 'faace.txt');
        $this->mcuserinfo->insertUserInfo($insertdata);
        $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
        if (empty($userinfo)) {
            return $this->getRC('fail', '99', '系统维护中');
        }
        $this->ltoken->set('userinfo', $userinfo);
        $this->ltoken->save();

        if (!$this->creatRole($userinfo['id'], $userinfo['mobile'], $userinfo['role'])) {
            return $this->getRC('fail', '98', '创建失败');
        }

        $result = array();
        $result['token'] = $this->ltoken->getToken();
        return $this->getRC('success', '', '', $result);
    }

    function sendsms_action() { // 3.	获取验证码
        if ($this->isMissInput('mobile', 'verifyCodeType')) { // 检查入口参数
            return;
        }

        $mobile = $this->getPosts('mobile');
        $vct = $this->getPosts('verifyCodeType');
        $this->loadLib('links/ltoken');
        $maxtimes = 50;
        switch ($vct) {
            case 1: { // 登录动态密码
                    // 检验是否有这个手机号码
                    $this->ltoken->token_encode($mobile, false); // 以手机方式保存token索引
                    $userinfo = $this->ltoken->get('userinfo');
                    if (empty($userinfo)) { // 至少还没有加载
                        $this->loadModule('links/mcuserinfo');
                        $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
                        if (empty($userinfo)) {
                            return $this->getRC('fail', '3', '手机号码不存在');
                        }
                        $this->ltoken->set('userinfo', $userinfo);
                        $this->ltoken->save();
                    }
                    // 检验今天是否已经发送了5次
                    $whichsms = 'sms1';
                    $smsinfo = $this->ltoken->get($whichsms);
                    if (!empty($smsinfo)) {
                        if (($smsinfo['date'] === date('Y-m-d')) && ($smsinfo['times'] >= $maxtimes)) {
                            return $this->getRC('fail', '4', '同一个手机号每天限发5条动态密码短信');
                        }
                    }
                    break;
                }
            case 2: { // 注册验证码
                    // 检验是否有这个手机号码
                    $this->loadModule('links/mcuserinfo');
                    $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
                    if (!empty($userinfo)) {
                        return $this->getRC('fail', '4', '手机号码已被人使用');
                    }

                    $this->ltoken->token_encode($mobile, false); // 以手机方式保存token索引
                    // 检验今天是否已经发送了5次
                    $whichsms = 'sms2';
                    $smsinfo = $this->ltoken->get($whichsms);
                    if (!empty($smsinfo)) {
                        if (($smsinfo['date'] === date('Y-m-d')) && ($smsinfo['times'] >= $maxtimes)) {
                            return $this->getRC('fail', '6', '同一个手机号每天限发5条验证码短信');
                        }
                    }
                    break;
                }
            case 3: { // 忘记密码验证码
                    $this->ltoken->token_encode($mobile, false); // 以手机方式保存token索引
                    $userinfo = $this->ltoken->get('userinfo');
                    if (empty($userinfo)) { // 至少还没有加载
                        $this->loadModule('links/mcuserinfo');
                        $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
                        if (empty($userinfo)) {
                            return $this->getRC('fail', '7', '手机号码不存在');
                        }
                        $this->ltoken->set('userinfo', $userinfo);
                        $this->ltoken->save();
                    }

                    // 检验今天是否已经发送了5次
                    $whichsms = 'sms3';
                    $smsinfo = $this->ltoken->get($whichsms);
                    if (!empty($smsinfo)) {
                        if (($smsinfo['date'] === date('Y-m-d')) && ($smsinfo['times'] >= $maxtimes)) {
                            return $this->getRC('fail', '8', '同一个手机号每天限发5条验证码短信');
                        }
                    }
                    break;
                }
            case 4: { // 修改手机号码验证码
                    if (!$this->ltoken->token_decode($this->getToken())) {
                        return $this->getRC('fail', '10000', 'token不存在');
                    }

                    // 检验是否有这个手机号码
                    $this->loadModule('links/mcuserinfo');
                    $userinfo = $this->mcuserinfo->getUserInfoByMobile($mobile);
                    if (!empty($userinfo)) {
                        return $this->getRC('fail', '11', '手机号码已被人使用');
                    }

                    // 检验今天是否已经发送了5次
                    $whichsms = 'sms4';
                    $smsinfo = $this->ltoken->get($whichsms);
                    if (!empty($smsinfo)) {
                        if (($smsinfo['date'] === date('Y-m-d')) && ($smsinfo['times'] >= $maxtimes)) {
                            return $this->getRC('fail', '12', '同一个手机号每天限发5条验证码短信');
                        }
                    }
                    break;
                }
            default: {
                    return $this->getRC('fail', '1', '请求参数值不正确（verifyCodeType）');
                }
        }

        if (empty($smsinfo) || $smsinfo['date'] != date('Y-m-d')) {
            $smsinfo = array('date' => date('Y-m-d'), 'times' => 0);
        }
        $this->loadLib('links/lsms');
        $code = $this->getRandCode(4, true);
//        var_dump($smsinfo);
//        var_dump($mobile);
//        var_dump($code);
//        exit;
        $rc = $this->lsms->sendSMS($code, $mobile);
        if ($rc['result'] == '01') {
            $smsinfo['times'] ++;
            $smsinfo['code'] = $code;
            $this->ltoken->set($whichsms, $smsinfo);
            // var_dump($this->ltoken->get($whichsms));
            $this->ltoken->save();
            return $this->getRC('success');
        } else {
            return $this->getRC('fail', '13', '短信发送失败，失败码：' . $rc['result']);
        }
    }

    function forgetpassword_action() { // 5.	忘记密码
        if ($this->isMissInput('mobile', 'verifyCode')) { // 检查入口参数
            return;
        }
        $mobile = $this->getPosts('mobile');
        $code = $this->getPosts('verifyCode');

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_encode($mobile, false)) {
            return $this->getRC('fail', '5', 'token无效');
        }

        $smsinfo = $this->ltoken->get('sms3');
        if ($smsinfo['code'] != $code) {
            return $this->getRC('fail', '4', '验证码错误');
        }
        // faacet $this->ltoken->del('sms3');
        $this->ltoken->save();

        $this->loadModule('links/mcuserinfo');
        $userinfo_temp = $this->mcuserinfo->getUserInfoByMobile($mobile);
        if (empty($userinfo_temp)) {
            return $this->getRC('fail', '3', '手机号码不存在');
        }

        $result = array();
        $result['token'] = $this->ltoken->getToken();
        return $this->getRC('success', '', '', $result);
    }

    function changemobile_action() { // 6.	修改手机号码
        if ($this->isMissInput('mobile', 'verifyCode')) { // 检查入口参数
            return;
        }

        $newmobile = $this->getPosts('mobile');
        $code = $this->getPosts('verifyCode');

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '10000', 'token不存在');
        }

        $this->loadModule('links/mcuserinfo');
        $userinfo_temp = $this->mcuserinfo->getUserInfoByMobile($newmobile);
        if (!empty($userinfo_temp)) {
            return $this->getRC('fail', '5', '手机号码已被人使用');
        }

        $userinfo = $this->ltoken->get('userinfo');
        if (empty($userinfo)) { // 至少还没有加载
            return $this->getRC('fail', '7', '用户信息不存在');
        }

        $smsinfo = $this->ltoken->get('sms4');
        if ($smsinfo['code'] != $code) {
            return $this->getRC('fail', '6', '验证码错误');
        }
        // faacet $this->ltoken->del('sms4');
        $this->ltoken->save();

        // 修改手机号
        $mobile = $userinfo['mobile'];
        $parm = array('mobile' => $newmobile);
        $this->mcuserinfo->update($parm, 'mobile="' . $mobile . '"');
        $userinfo['mobile'] = $newmobile;
        $this->ltoken->set('userinfo', $userinfo);
        $this->ltoken->save();

        return $this->getRC('success');
    }

    function changepw_action() { // 7.	修改密码
        if ($this->isMissInput('newPassword')) { // 检查入口参数
            return;
        }
        $newpw = $this->getPosts('newPassword');

        $this->loadLib('links/ltoken');
        if (!$this->ltoken->token_decode($this->getToken())) {
            return $this->getRC('fail', '3', 'token不存在11' . $this->getToken());
        }

        $userinfo = $this->ltoken->get('userinfo');
        if (empty($userinfo)) { // 至少还没有加载
            return $this->getRC('fail', '7', '用户信息不存在');
        }

        // 修改密码
        $this->loadModule('links/mcuserinfo');
        $mobile = $userinfo['mobile'];
        $parm = array('pw' => $newpw);
        $this->mcuserinfo->update($parm, 'mobile="' . $mobile . '"');
        $userinfo['pw'] = $newpw;
        $this->ltoken->set('userinfo', $userinfo);
        $this->ltoken->save();

        return $this->getRC('success');
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

    private function creatRole($accountid, $mobile, $role) {
        switch ($role) {
            case 6: {
                    return true;
                }
            case 8: { // 司机
                    $this->loadModule('links/mctrucker');
                    $truckerinfo = $this->mctrucker->getTruckerByAccountid($accountid);
                    if (empty($truckerinfo)) {
                        $truckerinfo = array();
                        $truckerinfo['accountid'] = $accountid;
                        $truckerinfo['status'] = 0;
                        $this->mctrucker->insert($truckerinfo, 'trucker');
                    }
                    return true;
                }
            default: {
                    return true;
                }
        }
        return false;
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
