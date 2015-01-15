<?php

include_once(WXLIB_PATH . '/wtemplate_base.class.php');

class wtemplate extends wtemplate_base { // m每个公众号的模板都不同，所以采用了父类继承的方式，把基本的代码放在base里面
//    public function __construct() {
//        parent::__construct();
//    }

    function test() { // http://weixin.buick.com.cn/wechat2/index.php?cl=wtemplate&fun=test
        //       $openid = 'oAtNguPrrueCR_LNcFcdzNc2MWYI'; // faace
//    $msg = array();
//    $msg['name'] = '客服通知';
//    $msg['remark'] = '您好，我是太平洋客服，我的微信号是faaceyu';
//    $msg['remark'] .= "\n\n" . '后台显示您用太卡购买咖啡后没出订单，请加我的微信号，确定您的信息正确后会把相关金额返还到您的太卡中。 ';
//    $msg['remark'] .= "\n\n" . '为您带来的不便敬请谅解。我们会更加努力为您服务的。谢谢您的支持，祝您生活愉快！';
//    $res = $this->sendBuyOk($openid, $msg, 'http://www.baidu.com');
//    var_dump($res);
    }

    function send04($parm) { // 积分交易提醒
// {{title.DATA}}
// 交易类型:{{type.DATA}}
// 交易积分:{{integral.DATA}}
// 当前可用积分：{{all.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'Hz-Uosl9ICmucc31UXigdT48pi7CJlT8dzNX_lVG88E'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send05($parm) { // 积分到期提醒
// {{title.DATA}}
// 您有{{Card.All.DATA}}积分即将于{{Card.Expire.DATA}}到期。
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'WqDhTSm1VtsFZGEcS2jVCXOZk2t3pwazoHnC-bFjHC8'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send06($parm) { // 会员升级提醒
// {{title.DATA}}
// 已升级会员：{{styles.DATA}}
// 有效期：{{period.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'V4sEldPrkLAiBoCP_bUiG_nrGqEOlLa19Oh_XW1PCfU'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send07($parm) { // 会员保级提醒
// {{title.DATA}}
// 您的{{name.DATA}}有效期至{{expDate.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'NNcryN-5-PhI07moUARYobA95gZvxvl_78YLLHTBj5c'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send08($parm) { // 会员降级提醒
// {{title.DATA}}
// 您的{{name.DATA}}有效期至{{expDate.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'NNcryN-5-PhI07moUARYobA95gZvxvl_78YLLHTBj5c'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send09($parm) { // 维保预约成功提醒
// {{title.DATA}}
// 预约{{productType.DATA}}：{{name.DATA}}
// 预约时间：{{time.DATA}}
// 预约结果：{{result.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'sh-yFbUpUvsw_8Ganaht82-ehJWs-vSaWDvpSeeyqwY'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send10($parm) { // 维保预约取消提醒
// {{title.DATA}}
// 预约{{productType.DATA}}：{{name.DATA}}
// 预约时间：{{time.DATA}}
// 预约结果：{{result.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'sh-yFbUpUvsw_8Ganaht82-ehJWs-vSaWDvpSeeyqwY'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send11($parm) { // 维保完成提醒
// {{title.DATA}}
// 日期：{{svrdate.DATA}}
// 经销商：{{dealername.DATA}}
// 服务项目：{{svrproject.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'zLgI3JT-llefXhyX62BEJLbFS6Hnw0ALxFmVq6MZEOc'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send12($parm) { // 违章提醒
// {{title.DATA}}
// 违章时间：{{violationTime.DATA}}
// 违章地点：{{violationAddress.DATA}}
// 违章内容：{{violationType.DATA}}
// 罚款金额：{{violationFine.DATA}}
// 扣分情况：{{violationPoints.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'gpTggOLnUxkmbJsy41ByBri-A2Wsb27TAF8s1t1koYk'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send13($parm) { // 维保预约前一天提醒
// {{title.DATA}}
// 预约{{productType.DATA}}：{{name.DATA}}
// 预约时间：{{time.DATA}}
// 预约结果：{{result.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'sh-yFbUpUvsw_8Ganaht82-ehJWs-vSaWDvpSeeyqwY'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send14_old($parm) { // 用车提醒
// {{title.DATA}}
// 预约{{productType.DATA}}：{{name.DATA}}
// 预约时间：{{time.DATA}}
// 预约结果：{{result.DATA}}
// {{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'sh-yFbUpUvsw_8Ganaht82-ehJWs-vSaWDvpSeeyqwY'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send14($parm) { // 用车提醒
//    {{first.DATA}}
//
//待办工作：{{work.DATA}}
//{{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'XIMXc6G0PA4jcvPk72Ih-exbHv2bdrOueygaKjAISbI'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

    function send20($parm) { // 中奖
//{{first.DATA}}
//开奖项目：{{program.DATA}} 中奖详情：{{result.DATA}}
//{{remark.DATA}}
        $data = array();
        $data['touser'] = $parm['openid'];
        $data['template_id'] = 'B7DrXiXiW2FkYQ7ywWuqOIbka0L-BZPXgKO1FJabqcE'; // 模板id
        $data['url'] = $parm['url']; // 点击跳转的链接
        $data['topcolor'] = '#cd0000'; // 颜色 '#FF0000';
        $data['data'] = $parm['msg'];

        return $this->send($parm['openid'], $data);
    }

}
