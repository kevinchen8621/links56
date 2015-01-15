<?php

class lcacheuserdata extends cbase { // 用来过滤关键字

    private $userdata = null;
    private $openid = null;

    public function __construct($openid) {
        $this->initMemcache();
    }

    public function __destruct() {
        if (!empty($this->openid)) {


            if (isset($this->userdata['location']['time']) && ((time() - $this->userdata['location']['time'])) > 600) { // 位置信息如果超过10分钟，就删除了
                unset($this->userdata['location']);
            } else {
                $this->userdata['location']['time'] = time();
            }
            $this->memcache->set($this->openid . '_userdata', $this->userdata, 0, 600); // 10 * 60s
        }
    }

    public function setOpenid($openid) {
        $this->openid = $openid;
        $this->initMemcache();
        $ud = $this->memcache->get($this->openid . '_userdata');
        $this->userdata = empty($ud) ? array() : $ud;
    }

    public function getUserinfo() {
        return $this->userdata;
    }

    public function setLocation($x, $y) {
        $location = array();
        $location['time'] = time();
        $location['x'] = $x; // 纬度 lat
        $location['y'] = $y; // 经度 lng
        $this->userdata['location'] = $location;
    }

    public function getLocation() {
        return empty($this->userdata['location']) ? null : $this->userdata['location'];
    }

    public function getSrvName() {
        return isset($this->userdata['srvname']) ? $this->userdata['srvname'] : '';
    }

    public function setSrvName($name) {
        $this->userdata['srvname'] = $name;
    }

}
