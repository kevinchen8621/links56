<?php
class xbase{
    public function header_token (){
        if(empty($_SERVER['token'])){
            return '';
        }else{
            return $_SERVER['token'];
        }
    }
    function success($array=null){
        $result =array();
        $result['status'] = 'success';
        if($array!=null){$result['result'] = $array;}
        echo preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($result));
        die;
    }
    function failed($id,$msg){
        $result = array();
        $result['status'] = 'fail';
        $result["failCode"] = $id;
        $result["failMessage"] = $msg;
        echo preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '$1'))", json_encode($result));
        die;
    }
    function check($userinfo,$md = true){
        if(empty($userinfo)){
            $this->failed(99,'memcached NULL');
        }

        if($userinfo['role']!=6 && $md==true){
            $this->failed(99,'不是货主身份');
        }

        if($userinfo['status']!=1){
            $this->failed(99,'黑名单用户');
        }
    }
    function checkpost($key){
        if(isset($_POST[$key])){
            return $_POST[$key];
        }else{
            $this ->failed(1,'请求参数缺失('.$key.')');
        }
    }

    function len3($key){
        if(count($key)!=3){
            $this -> failed(99,'地址类参数 必须为LEN3格式');
        }
    }
}