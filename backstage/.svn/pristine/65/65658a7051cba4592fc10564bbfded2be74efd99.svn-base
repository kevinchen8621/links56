<?php

class cpublic_controller extends cbase {

    function __construct() {
        parent::__construct();
        $this->initMemcache();
    }

// UPDATE  `backstage`.`city` SET  `pinyin` = TRIM(  `short_pinyin` )
    function getName() {
        return 'cpublic';
    }

//    function tt_action() {
//        $this->loadLib('public/lpinyin');
//        echo $this->lpinyin->getInitial('邻客物流');
//        echo '<br/>';
//        echo $this->lpinyin->getPinyin('邻客物流');
//    }
//
//    function fix_action() {
//        set_time_limit(0);
//        $this->loadLib('public/lpinyin');
//        $this->loadModule('links/mcpublic');
//        $ps = $this->mcpublic->getAllInfo('district');
//        $this->mcpublic->begin();
//        foreach ($ps as $p) {
//            $p['pinyin'] = $this->lpinyin->getPinyin($p['name']);
//            $p['short_pinyin'] = $this->lpinyin->getInitial($p['name']);
//            $id = $p['id'];
//            unset($p['id']);
//            $this->mcpublic->update($p, '`id`="'.$id.'"', 'district');
//        }
//        $this->mcpublic->commit();
//    }

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
                echo $this->getRC('fail', '1', '请求参数缺失（' . $name . '）');
                return true;
            }
        }
        return false;
    }

    function getparm_action() { // 获取最新参数表
        if ($this->isMissInput('currentVersion')) { // 检查入口参数
            return;
        }

        $curversion = $this->getPosts('currentVersion');
        $key = md5('public_parameters');
        $this->initMemcache();
        $parms = $this->memcache->get($key);

        if (empty($parms)) { // 没加载到缓冲，加载先
            $alldata = $this->loadfile('links/public_parameters.txt', 10);
            if (empty($alldata)) {
                set_time_limit(300); // 5分钟如果不行就不行了
                $this->loadModule('links/mcpublic');
                $allparm['bank'] = $this->mcpublic->getAllInfo('bank');
                $allparm['truck_type'] = $this->mcpublic->getAllInfo('truck_type');
                $allparm['truck_length'] = $this->mcpublic->getAllInfo('truck_length');
                $allparm['truck_load'] = $this->mcpublic->getAllInfo('truck_load');
                $allparm['truck_body'] = $this->mcpublic->getAllInfo('truck_body');
                $allparm['truck_special'] = $this->mcpublic->getAllInfo('truck_special');
                $allparm['truck_brand'] = $this->mcpublic->getAllInfo('truck_brand');
                $allparm['area'] = $this->mcpublic->getArea();
                $alldata = preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($allparm));
                $this->savefile($alldata, 'links/public_parameters.txt', 'w');
                $parms['version'] = $this->mcpublic->getVersion('parameter');
                $this->memcache->set($key, $parms);
            }
        }
        if ($curversion == $parms['version']) { // 版本相同，不需要再发
            echo $this->getRC('success');
            return;
        }


        if (empty($alldata)) {
            $alldata = $this->loadfile('links/public_parameters.txt');

            if (empty($alldata)) {
                set_time_limit(300); // 5分钟如果不行就不行了
                $this->loadModule('links/mcpublic');
                $allparm['bank'] = $this->mcpublic->getAllInfo('bank');
                $allparm['truck_type'] = $this->mcpublic->getAllInfo('truck_type');
                $allparm['truck_length'] = $this->mcpublic->getAllInfo('truck_length');
                $allparm['truck_load'] = $this->mcpublic->getAllInfo('truck_load');
                $allparm['truck_body'] = $this->mcpublic->getAllInfo('truck_body');
                $allparm['truck_special'] = $this->mcpublic->getAllInfo('truck_special');
                $allparm['truck_brand'] = $this->mcpublic->getAllInfo('truck_brand');
                $allparm['area'] = $this->mcpublic->getArea();
                $alldata = preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($allparm));
                $this->savefile($alldata, 'links/public_parameters.txt', 'w');
                $parms['version'] = $this->mcpublic->getVersion('parameter');
                $this->memcache->set($key, $parms);
            }
            if (empty($alldata)) {
                echo $this->getRC('fail', '99', '系统维护中');
                return;
            }
        }

        $bank = $this->getPosts('bank');
        $truck_type = $this->getPosts('truckType');
        $truck_length = $this->getPosts('truckLength');
        $truck_load = $this->getPosts('truckLoad');
        $truck_body = $this->getPosts('truckBody');
        $truck_special = $this->getPosts('truckSpecial');
        $truck_brand = $this->getPosts('truckBrand');
        $area = $this->getPosts('area');

        $result = json_decode($alldata, true);
        if (empty($bank)) {
            unset($result['bank']);
        }
        if (empty($truck_type)) {
            unset($result['truck_type']);
        }
        if (empty($truck_length)) {
            unset($result['truck_length']);
        }
        if (empty($truck_load)) {
            unset($result['truck_load']);
        }
        if (empty($truck_body)) {
            unset($result['truck_body']);
        }
        if (empty($truck_special)) {
            unset($result['truck_special']);
        }
        if (empty($truck_brand)) {
            unset($result['truck_brand']);
        }
        if (empty($area)) {
            unset($result['area']);
        }
        echo $this->getRC('success', '', '', $result);
        return;
    }

    function getdist_action() {
        if ($this->isMissInput('cityId')) { // 检查入口参数
            return;
        }
        $this->loadModule('links/mcpublic');
        $cityId = $this->getPosts('cityId');
        $result = $this->mcpublic->getList('`cityid`=' . $cityId, 0, 1000, 'district');
        echo $this->getRC('success', '', '', $result);
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
        return preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($rc));
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
