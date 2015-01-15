<?php

class uploadimg_controller extends cbase {

    function getName() {
        return 'uploadimg';
    }

    function index_action() {
        //demo文件，阅毕删除。
    }

    function a_action() {
        $this->loadHtml('aaa');
    }

    function b_action(){
        $this->loadLib('qcloud/Cos');
        $cos_obj = $this->Cos;

        $upfile = $this->getFiles();
        $arr=array(
            '123.jpg'=>file_get_contents($upfile['tmp_name'])
        );

        var_dump($cos_obj->upload_x($arr));
    }

}
