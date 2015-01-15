<?php

class hbaidu {

    function geocoding($address, $city = null) { // 获取经纬度
        $msg = '&address=' . urlencode($address); // 对地址作url转码,防止乱码产生影响结果集
        if (isset($city)) {
            $msg .= '&City=' . $city;
        }
        $url = 'http://api.map.baidu.com/geocoder/v2/?output=json&ak=VZorfeDEhz0t3gr6FIBGzXMq' . $msg;
        $rc = file_get_contents($url);
        $pos = preg_replace("#\\\u([0-9a-f]{4}+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $rc); // 对json的汉字部分转码
        $json = json_decode($pos, true);
        return isset($json['result']['location']) ? $json['result']['location'] : '';
    }

    function getyy($lng) {
        return $lng * 20037508.342789 / 180;
    }

    function getxx($lat) {
        $d = log(tan((90 + $lat) * M_PI / 360)) / (M_PI / 180);
        return $d * 20037508.342789 / 180;
    }

}
