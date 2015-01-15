<?php

// 1.userinfo
// 2. sms1, sms2, sms3, sms4
// 3. mobile
// 4. ts
class ltoken extends ccommon { // 发送短信

    private $ts = null;
    private $memtoken = null;
    private $allinfo = null;

    public function __construct() {
        parent::__construct();
    }

    public function isEmpty() {
        if (empty($this->allinfo)) {
            $this->initMemcache();
            $this->allinfo = $this->memcache->get($this->memtoken);
        }
        return empty($this->allinfo);
    }

    public function get($key) {
        if (empty($this->allinfo)) {
            $this->initMemcache();
            $this->allinfo = $this->memcache->get($this->memtoken);
        }
        return isset($this->allinfo[$key]) ? $this->allinfo[$key] : null;
    }

    public function getall() {
        if (empty($this->allinfo)) {
            $this->initMemcache();
            $this->allinfo = $this->memcache->get($this->memtoken);
        }
        return isset($this->allinfo) ? $this->allinfo : null;
    }

    public function set($key, $data) {
        if (empty($this->allinfo)) {
            $this->initMemcache();
            $this->allinfo = $this->memcache->get($this->memtoken);
        }
        return $this->allinfo[$key] = $data;
    }

    public function del($key) {
        if (empty($this->allinfo)) {
            $this->initMemcache();
            $this->allinfo = $this->memcache->get($this->memtoken);
        }
        unset($this->allinfo[$key]);
    }

    public function save() {
        if (empty($this->allinfo)) {
            $this->memcache->delete($this->memtoken);
            return;
        }
        $this->memcache->set($this->memtoken, $this->allinfo);
    }

    function token_decode($combintoken) {
        // combine token由多部分主程，第一部分是时间的md5， 第二是手机的md5，第三是？？
        // $combintoken = $this->getServers('HTTP_TOKEN');
        if (empty($combintoken) || (strlen($combintoken) < 64)) {
            return false;
        }

        $st = str_split($combintoken, 32);
        $this->ts = $st[0];
        $this->memtoken = $st[1];

        if (empty($this->allinfo)) {
            $this->initMemcache();
            $this->allinfo = $this->memcache->get($this->memtoken);
        }
        return !empty($this->allinfo);
    }

    function token_encode($memtoken, $notmobile = true) {
        $this->ts = md5(date('Y-m-d'));
        // $this->set($this->ts, date('Y-m-d H'));
        $this->memtoken = $notmobile ? $memtoken : md5('sign_' . $memtoken);
        return $this->getToken();
    }

    function getMemToken() {
        return $this->memtoken;
    }

    function getToken() {
        return $this->ts . $this->memtoken;
    }

}
