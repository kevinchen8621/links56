<?php

class lwmenu {

    function __construct() {
        if (include_once(LIB_PATH . '/lfilter.class.php')) {
            $this->lfilter = new lfilter();
        }
    }

    function test() { // 测试专用 
        $data = array(
            array(
                'name' => '一',
                'children' => array(
                    array('name' => '测试', 'parm' => '测试', 'type' => '1'),
                    array('name' => '测试1', 'parm' => '测试1', 'type' => '1'),
                    array('name' => '测试2', 'parm' => '测试2', 'type' => '1')
                )
            ),
            array(
                'name' => '二',
                'children' => array(
                    array('name' => '关键字', 'parm' => 'fabu', 'type' => '1'),
                    array('name' => '外部链接', 'parm' => 'http://www.baidu.com', 'type' => '2'),
                    array('name' => 'auth链接', 'parm' => 'http://www.yiwidnow.com', 'type' => '3'),
                    array('name' => '内部链接id', 'parm' => 'asdfDDDsdf', 'type' => '4'),
                    array('name' => '关键字', 'parm' => 'fabu2', 'type' => '1'),
                )
            ),
            array('name' => '三', 'parm' => 'fabu', 'type' => '1')
        );
        echo '<pre/>';
        $rc = $this->createMenu($data);
        $rc = $this->getMenu();
        var_dump(json_decode($rc, true));
    }

    function createMenu($data) {
        if (!include_once('lwauth.class.php'))
            return false;
        $wauth = new lwauth();
        foreach ($data as $menus) {
            if (!empty($menus['children'])) {
                $children = array();  //定义child是数组，同时循环时清空数组
                foreach ($menus['children'] as $v) {
                    $children[] = $this->buildButton($v['type'], $v['name'], $v['parm'], $wauth);
                }
                $arr['button'][] = $this->buildButton(0, $menus['name'], $children, $wauth);
            } else {
                $arr['button'][] = $this->buildButton($menus['type'], $menus['name'], $menus['parm'], $wauth);
            }
        }
        //  return $arr;
        // $submitstr = preg_replace("#\\\u([0-9a-f]{4}+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", json_encode($arr));
        $submitstr = preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($arr));
        // preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($arr));
        if (!empty($this->lfilter)) { // 如果需要过滤就过滤
            $submitstr = $this->lfilter->filt($submitstr);
        }

 
        $rc = $this->send($submitstr);
        return $rc;
    }

    private function buildButton($type, $name, $parm, $wauth) {
        $but = array();
        switch ($type) {
            case 1: { // 关键字
                    $but['type'] = 'click';
                    $but['key'] = $parm;
                    break;
                }
            case 2: { // 外部链接
                    $but['type'] = 'view';
                    $but['url'] = $parm;
                    break;
                }
            case 3: { // auth链接
                    $parm = explode('|', $parm);
                    $url = $wauth->getUrl(array('url' => $parm[0], 'state' => (isset($parm[1]) ? $parm[1] : 'fw')));
                    $but['type'] = 'view';
                    $but['url'] = $url;
                    break;
                }
            case 4: { // 内部链接id
                    $but['type'] = 'view';
                    global $env;
                    $url = $env['http'] . 'index.php?a=public.cweb&id='; // 我们要做的功能
                    $but['url'] = $url . $parm;
                    break;
                }
            case 5: { // 扫码推事件
                    $but['type'] = 'scancode_push';
                    $but['key'] = $parm;
                    break;
                }
            case 6: { // 弹出拍照或者相册发图
                    $but['type'] = 'pic_photo_or_album';
                    $but['key'] = $parm;
                    break;
                }
            default: { // 菜单
                    $but['sub_button'] = $parm;
                    break;
                }
        }
        $but['name'] = $name;
        return $but;
    }

    private function send($data, $again = 1) {
        if (!include_once('lwtoken.class.php'))
            return false;
        $wtoken = new lwtoken();
        $token = $wtoken->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;
        $context = array();
        $context['http'] = array(
            'method' => 'POST',
            'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US)\r\nAccept: */*\r\nContent-type:application/x-www-form-urlencoded",
            'content' => $data
        );
        $rc = file_get_contents($url, false, stream_context_create($context));
        $res = json_decode($rc, true);
        if ($again > 0) {
            if ('' . $res['errcode'] == '40001') {
                return $this->send($data, --$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        return $rc;
    }

    function getMenu($again = 1) {
        if (!include_once('lwtoken.class.php'))
            return false;
        $wtoken = new lwtoken();
        $token = $wtoken->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=' . $token;
        $rc = file_get_contents($url);
        $res = json_decode($rc, true);
        if ($again > 0) {

            if ('' . @$res['errcode'] == '40001') {
                return $this->getMenu( --$again); // 第二次发送就要标识以下，否则会死循环
            }
        }
        return $rc;
    }

}
