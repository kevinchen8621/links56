<?php

abstract class cwxbase extends cbase { // 初始化程序需要的所有东西

    abstract function getDesc(); // 获取这个服务的功能

    abstract function exe($loop, $parm); // 实现功能的地方

    function __construct() {
        parent::__construct();
    }

    function setUserData($svrname, $parm) {
        $userdata = &$GLOBALS['userdata'];
        $userdata['srvname'] = $svrname; // 设置好
        $userdata['parm'] = $parm;
    }

    function resetUserData() {
        $GLOBALS['userdata'] = '';
    }

    function getUserData($svrname) {
        $userdata = &$GLOBALS['userdata'];
        return ($svrname == $userdata['srvname']) ? $userdata['parm'] : '';
    }

    function setRep($msg) {
        switch (@$msg['type']) {
            case 1: { // text
                    return $this->setRspText($msg['intro']);
                }
            case 2: { // music
                    return $this->setRspMusic($msg['title'], $msg['url'], $msg['url2']);
                }
            case 4: { // news
                    // $news = $dmsgconst->getNews($msg['ext']);
                    if (!empty($msg['news'])) {
                        return $this->setRspNews($msg['news']);
                    }
                }
        } // switch ($msg['type']) {
        return false;
    }

    function setRspCustomer($KfAccount = false) { // 产生文本消息内容
        $rsp = &$GLOBALS['rsp'];
        $rsp['MsgType'] = 'transfer_customer_service';
        $rsp['KfAccount'] = $KfAccount;
        return true;
    }

    function setRspText($content) { // 产生文本消息内容
        $rsp = &$GLOBALS['rsp'];
        $rsp['MsgType'] = 'text';
        $rsp['Content'] = $content;
        return true;
    }

    function setRspMusic($title, $url, $hqurl) { // 产生音乐消息内容
        $rsp = &$GLOBALS['rsp'];
        $rsp['MsgType'] = 'music';
        $rsp['Title'] = $title;
        $rsp['Description'] = '';
        $rsp['MusicUrl'] = $url;
        $rsp['HQMusicUrl'] = $hqurl;
        return true;
    }

    function setRspNews($news) { // 产生图文消息内容
        $rsp = &$GLOBALS['rsp'];
        $rsp['MsgType'] = 'news';
        $item = array();
        foreach ($news as $n) {
            $i = array();
            $i['Title'] = $n['title'];
            $i['Description'] = $n['intro'];
            $i['PicUrl'] = $n['url'];
            $i['Url'] = $n['url2'];
            $item[] = $i;
        }

        $rsp['item'] = $item;
        return true;
    }

    function logDB($type, $name, $data = '', $tag = 0) {
        $parm = array();
        $parm['openid'] = $GLOBALS['openid'];
        $parm['type'] = $type;
        $parm['data'] = $data;
        $parm['name'] = $name;
        $parm['tag'] = $tag;
        // $this->runDB('dilogs', 'insert', $parm);
    }

}
