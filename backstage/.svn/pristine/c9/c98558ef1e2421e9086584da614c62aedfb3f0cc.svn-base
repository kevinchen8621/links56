<?php

class ctest_controller extends cbase {

    function getName() {
        return 'ctest';
    }

    function index_action() {
        $token = '6740f06b230a52f04bad9be0d68e3a2c0b791bbd72befe3fc2ea4fe77409ae9f';
        $this->loadLib('links/ltoken');
//         $this->ltoken->token_decode($token);
//         $rc = $this->ltoken->getall();

        $url = 'http://localhost/backstage/links/cuserinfo/changepw.html';
        $data = array();
        $data['newPassword'] = '654321';
        $rc = $this->send($data, $url, $token);
        var_dump($rc);
//        $this->wxOnly(); // 只能在微信上打开
//        $this->wxshare('http://www.baidu.com/img/baidu_sylogo1.gif', 'http://www.baidu.com', '欢迎多交流', '大家好，我是faace', 'wxindex.php?con=wxtest&act=cb');
//        // $this->wxHideOptionMenu();
//
//        $this->loadHtml2('index');
    }

    function sendsms_action() { // 发送验证码
        $url = 'http://localhost/backstage/links/cuserinfo/sendsms.html';
        $data = array();
        $data['mobile'] = '18688931217'; // '18688931217';
        $data['verifyCodeType'] = 2; // // 注册验证码
        // $data['verifyCodeType'] = 3; // // 忘记密码
        // $data['verifyCodeType'] = 4; // // 修改手机号码
        // $token = '0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378';
        // $data['verifyCodeType'] = 1; // // 动态登录密码

        $rc = $this->send($data, $url);
        var_dump($rc);
    }

    function register_action() { // 发送验证码
        $url = 'http://localhost/backstage/links/cuserinfo/register.html';
        $data = array();
        $data['mobile'] = '18588821107';
        $data['password'] = '123456'; // // 注册验证码
        $data['verifyCode'] = '5817'; // '5817'; // // 注册验证码
        $data['role'] = 6; // '5817'; // // 注册验证码
        $rc = $this->send($data, $url);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function forgetpassword_action() { // 忘记密码
        $url = 'http://localhost/backstage/links/cuserinfo/forgetpassword.html';
        $data = array();
        $data['mobile'] = '18588821106';
        $data['verifyCode'] = '1871'; // '5817'; // // 注册验证码
        $token = '0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378';
        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function changemobile_action() { // 6.	修改手机号码
        $url = 'http://localhost/backstage/links/cuserinfo/changemobile.html';
        $data = array();
        $data['mobile'] = '18588821106';
        $data['verifyCode'] = '7985'; // '5817'; // // 注册验证码
        $token = '0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378';
        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function changepw_action() { // 7.	修改密码
        $url = 'http://localhost/backstage/links/cuserinfo/changepw.html';
        $data = array();
        $data['newPassword'] = '654321';
        $token = '0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378';
        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function signin_action() { // 1.	普通登录
        $url = 'http://localhost/backstage/links/cuserinfo/signin.html';
        $data = array();
        $data['mobile'] = '18688931217';
        $data['password'] = '654321';
        $rc = $this->send($data, $url);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function actsignin_action() { // 2.	动态密码登录
        $url = 'http://localhost/backstage/links/cuserinfo/actsignin.html';
        $data = array();
        $data['mobile'] = '18588821107';
        $data['dynamicPassword'] = '7804';
        $rc = $this->send($data, $url);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function getparm_action() { // 获取参数表
        $url = 'http://localhost/backstage/links/cpublic/getparm.html';
        $data = array();
        $data['currentVersion'] = '0.0.0';
        $data['area'] = true;
        $rc = $this->send($data, $url);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function getdist_action() { // 获取城市里面的区
        $url = 'http://localhost/backstage/links/cpublic/getdist.html';
        $data = array();
        $data['cityId'] = '465';
        $rc = $this->send($data, $url);
        var_dump($rc);
        // 0241c0a11c6a6291a74d1e9a7f6948d7536ef6407b48b3ae0ca75cbc035a1378
    }

    function getaccountinfo_action() { // 8.	获取账号详情
        $url = 'http://localhost/backstage/links/ctrucker/getaccountinfo.html';

        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function requestautho_action() { // 9.	申请身份认证
        $url = 'http://localhost/backstage/links/ctrucker/requestauth.html';

        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $data['head'] = base64_encode($this->loadfile('img/tt.jpg'));
        $data['name'] = '余君山'; // 姓名
        $data['idCardNumber'] = '111111111111111'; // 身份证号
        $data['idCard'] = base64_encode($this->loadfile('img/tt.jpg'));
        $data['driverLicense'] = base64_encode($this->loadfile('img/0.jpg'));
        $data['travelCardMainPage'] = base64_encode($this->loadfile('img/tt.jpg'));
        $data['travelCardSubPage'] = base64_encode($this->loadfile('img/0.jpg'));
        $data['trailerTravelCard'] = base64_encode($this->loadfile('img/tt.jpg'));

        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function getauth_action() { // 10.	获取身份认证
        $url = 'http://localhost/backstage/links/ctrucker/getauth.html';

        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();

        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function changehead_action() { // 11.	修改头像
        $url = 'http://localhost/backstage/links/ctrucker/changehead.html';

        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $data['head'] = base64_encode($this->loadfile('img/tt.jpg'));
        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function changetrucker_action() { // 12.	修改车辆信息
        $url = 'http://localhost/backstage/links/ctrucker/changetrucker.html';

        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $data['truckModel'] = '2'; // 车型
        $data['truckNumber'] = '2'; // 车牌号
        $data['truckLength'] = '2'; // 车长
        $data['Ton'] = '2'; // 吨位
        $data['truckBox'] = '2'; // 货箱
        $data['truckType'] = '2'; // 货车类型
        $data['truckBrand'] = '2'; // 货车品牌
        $data['truckNumberPhoto'] = base64_encode($this->loadfile('img/tt.jpg')); // 车牌图片
        $data['truckPhoto'] = base64_encode($this->loadfile('img/0.jpg')); // 货车照片图片

        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function getbanks_action() { // 13.	获取银行卡列表
        $url = 'http://localhost/backstage/links/ctrucker/getbanks.html';
        $token = 'b520b7bad0a2dbb40e0255c704348186e81570ad4951c4b34dbd5e256ece6525';
        $data = array();

        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function updatebank_action() { // // 14.	修改银行卡信息
        $url = 'http://localhost/backstage/links/ctrucker/updatebank.html';
        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $data['bankCardId'] = 2;
        $data['name'] = '余君山';
        $data['bankAreaIdList'] = '11,8';
        $data['bankAreaList'] = '广东,广州';
        $data['bank'] = '农业银行';
        $data['bankCardNumber'] = '12346566767';

        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function addbank_action() { // 15.添加银行卡信息
        $url = 'http://localhost/backstage/links/ctrucker/addbank.html';
        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $data['name'] = '余君山';
        $data['bankAreaIdList'] = '11,8';
        $data['bankAreaList'] = '广东,广州';
        $data['bank'] = '招商银行';
        $data['bankCardNumber'] = '5675467435345345';


        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function delbank_action() { // 17.	删除银行卡信息
        $url = 'http://localhost/backstage/links/ctrucker/delbank.html';
        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();
        $data['bankCardId'] = '3';


        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function getrank_action() { // 16.	获取司机排名列表
        $url = 'http://localhost/backstage/links/ctrucker/getrank.html';
        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();


        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function getbidinglist_action() { // 19.获取当前抢单列表
//        $list = array();
//        $l = array();
//        $l['id'] = '2';
//        $l['waybillid'] = '1';
//        $l['accountid'] = '4';
//        $l['name'] = 'xxxx';
//        $l['number'] = '12';
//        $l['weight'] = '22';
//        $l['total_weight'] = '12312';
//        $l['length'] = '2';
//        $l['width'] = '3';
//        $l['height'] = '2';
//        $l['total_volume'] = '123123';
//        $list[] = $l;
//
//        echo json_encode($list);exit;

        $url = 'http://localhost/backstage/links/cwaybill/getbidinglist.html';
        $token = '7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073';
        $data = array();


        $rc = $this->send($data, $url, $token);
        var_dump($rc);
        // 7895ed5abc06c7ba09df438371cfb1fbed783e8f3fbe2600cb537730fe2e9073
    }

    function cb_action() {
        hlog::logDebug('faace7', var_export($_GET, true));
    }

    private function send($data, $url, $token = '') {
        $context = array();
        $content = http_build_query($data);
        $header = "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type: application/x-www-form-urlencoded\r\n";
        if (!empty($token)) {
            $header.='token: ' . $token;
        }
        $context['http'] = array(
            'method' => 'POST',
            // 'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/json\r\ntOken:xxxx",
            'header' => $header,
            'content' => $content // preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($data))
        );

        $rc = file_get_contents($url, false, stream_context_create($context));
        var_dump($rc);
        return json_decode($rc, true);
    }

}
