<?php

class mcpublic extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'bank');
    }

    function getArea() {
        $province = $this->getAllInfo('province');
        foreach ($province as $pk => $p) {
            $city = $this->getList('`provinceid`=' . $p['id'], 0, 1000, 'city');
            if (!empty($city)) {
//                // 不要区             
//                foreach ($city as $ck => $c) {
//                    if ($c['child_num'] > 0) {
//                        $city[$ck]['district'] = $this->getList('`cityid`=' . $c['id'], 0, 1000, 'district');
//                    }
//                }
                $province[$pk]['city'] = $city;
            }
        }
        return $province;
    }

    public function getAllInfo($name) { // 
        if (empty($name)) {
            return null;
        }
        $sql = "SELECT * FROM `{$name}`";
        return $this->db->select($sql);
    }

    public function getVersion($name) { // 
        $sql = "SELECT version FROM `version` WHERE `name`='{$name}'";
        $v = $this->db->fetch_first($sql);
        return isset($v['version']) ? $v['version'] : '0.0.-1';
    }

    public function getTruckLength($idx) { // 车长参数表
        $this->initMemcache();
        $trucklength = $this->memcache->get('public_trucklength');
        if (empty($trucklength)) { // 加载到memcache中
            $all = $this->getList(1, 0, 1000, 'truck_length');
            $trucklength = array();
            foreach ($all as $a) {
                $trucklength[$a['id']] = $a['truck_length'];
            }
            $this->memcache->set('public_trucklength', $trucklength);
        }
        return isset($trucklength[$idx]) ? $trucklength[$idx] : '待定';
    }

}
