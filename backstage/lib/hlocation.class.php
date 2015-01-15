<?php

class wlocation {

    public $lat = '';
    private $lng = '';
    public $openid = '';
    public $db = '';

    function __construct($lat, $lng, $openid = '', &$db = '') {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->openid = $openid;
        $this->db = $db;
    }

    function getDistance($arrstore) { // 
        $y = $this->getxx(); //微信纬度 lat
        $x = $this->getyy(); //微信经度 lng

        $ar = $this->getStore($arrstore, $x, $y);

        if (!empty($ar)) {
            return $this->getSrotStore($ar, $ar[0]['city']); // 返回要封装的图文消息
        }
        return false;
    }

    function getyy() { // 获取墨卡托经度
        return $this->lng * 20037508.342789 / 180;
    }

    function getxx() { // 获取墨卡托纬度
        $d = log(tan((90 + $this->lat) * M_PI / 360)) / (M_PI / 180);
        return $d * 20037508.342789 / 180;
    }

    function array_sort($arr, $keys, $type = 'asc') { // 根据$keys离排序
        //file_put_contents('ss.txt', $keys);
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }

        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }

        reset($keysvalue);

        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }

        return $new_array;
    }

    private function getStore($arrstore, $x, $y) { // 获取范围内的4S店
        $count = count($arrstore);
        if ($count > 0) {
            $j = 0;
            for ($i = 0; $i < $count; $i++) {
                $distance = intval(sqrt(pow(($arrstore[$i]['y'] - $x), 2) + pow(($arrstore[$i]['x'] - $y), 2)));
                if ($distance <= 30000) {
                    $ar[$j]['distance'] = sprintf("%.2f", $distance * 0.001);
                    $ar[$j]['name'] = $arrstore[$i]['name'];
                    $ar[$j]['address'] = $arrstore[$i]['address'];
                    $ar[$j]['lat'] = $arrstore[$i]['lat'];
                    $ar[$j]['lng'] = $arrstore[$i]['lng'];
                    $ar[$j]['city'] = $arrstore[$i]['city'];
                    $ar[$j]['id'] = $arrstore[$i]['id'];
                    for ($k = 1; $k < 6; $k++) {
                        if ($arrstore[$i]['check' . $k] == 1) {
                            $pic = $arrstore[$i]['pic' . $k];
                        }
                        break;
                    }
                    $ar[$j]['pic1'] = $pic;
                    $j++;
                }
            }
            return $ar;
        }
        return false;
    }

    private function getSrotStore($arrstore, $city) { // 封装4S店
        $arr = $this->array_sort($arrstore, 'distance'); // 根据获得的距离排序
        $count = count($arr);
        $i = 0;
        $limit = 3;
        $info = array();

        if ($this->openid) {
            if ($this->openid != 'ovU8Mj6Dfln_7bjuU4FTfPzLXxpw')
                if ($myinfo = $this->db->getmycar($this->openid)) {
                    $y = $this->getxx(); //微信纬度 lat
                    $x = $this->getyy(); //微信经度 lng
                    // file_put_contents('arr.txt', $myinfo['x']);
                    $distance = sprintf("%.2f", 0.001 * intval(sqrt(pow(($myinfo['y'] - $x), 2) + pow(($myinfo['x'] - $y), 2))));
                    //$distance1 = intval(sqrt(pow(($myinfo['y'] - $x), 2) + pow(($myinfo['x'] - $y), 2)));
                    //   $distance2 = substr($distance1 * 0.001, 0, 4);
                    $info[$i]['title'] = $myinfo['name'] . ' ' . '距离您' . ' ' . $distance . ' ' . '公里';
                    $info[$i]['intro'] = $myinfo['address'] . ' ' . '距离您' . ' ' . $distance . ' ' . '公里';
                    $info[$i]['distance'] = $distance;
                    $info[$i]['url'] = '#HOST#/buick/views/default/img/index.jpg'; //店铺的封面;
                    $info[$i]['url2'] = '#HOST#/buick/wxindex.php?con=cwnearstore&act=index&id=' . $myinfo['id'] . '&lat_x=' . $this->lng . '&lat_y=' . $this->lat;
                    $i = 1;
                }
        }

        foreach ($arr as $v) {
            if ($i == 0) {
                // if($v['pic1'] == ''){
                $v['pic1'] = '/buick/views/default/img/index.jpg';
                //   }
            } else {
                // if($v['pic1'] == ''){
                $v['pic1'] = '/buick/views/default/img/logoimg.jpg';
                //  }
            }
            //   file_put_contents('pic.txt', $pic);
            $info[$i]['title'] = $v['name'] . ' ' . '距离您' . ' ' . $v['distance'] . ' ' . '公里';
            $info[$i]['intro'] = $v['address'] . ' ' . '距离您' . ' ' . $v['distance'] . ' ' . '公里';
            $info[$i]['distance'] = $v['distance'];
            $info[$i]['url'] = '#HOST#' . $v['pic1']; //店铺的封面;
            $info[$i]['url2'] = '#HOST#/buick/wxindex.php?con=cwnearstore&act=index&id=' . $v['id'] . '&lat_x=' . $this->lng . '&lat_y=' . $this->lat;
            if (++$i >= $limit)
                break;
        }
        $info[$count + 1]['title'] = $city . '所有4S店列表';
        $info[$count + 1]['intro'] = '';
        $info[$count + 1]['url'] = '#HOST#/buick/views/default/img/bk22.jpg';
        $info[$count + 1]['url2'] = '#HOST#/buick/' . 'wxindex.php?con=cwnearstore&act=getCity&lat_x=' . $this->lng . '&lat_y=' . $this->lat . '&city=' . urlencode($city);
        //  file_put_contents('arr.txt', var_export($info, true));
        return $info;
    }

}
