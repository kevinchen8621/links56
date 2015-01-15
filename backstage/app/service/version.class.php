<?php

class version_controller extends cbase{
    //getname
    function getName(){return 'version';}

    //获取最新版本号
    //曹返
    //2015/1/11
    function index_action(){
        $this->loadModule('links/version');
        $this->loadLib('shipper/xbase');
        $kind = $this ->xbase->checkpost('kind');
        $rc = $this->version->getnewversion($kind);
        if(empty($rc)){
            $this -> xbase ->failed(2,'请求参数值不正确(kind)');
        }
        $this -> xbase ->success(array('version'=>$rc));
    }
}