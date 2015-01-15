<?php

class demo_controller extends cbase {

    function index_action() {
//        $cc = array();
//        $cc['cc'] = 'aa';
//        $cc['c33c'] = 'cc';
//        echo json_encode($cc);
//        exit;
        $a = 'aaa';
        var_dump(get_variable_name($a));
        return;
        // $this->loadLib('links/ltoken');
        // echo md5(1);
        var_dump(str_split('123123123123123123', 10));
//        $this->initMemcache();
//        $this->memcache->set('a', 'bbb', 0, 10);
    }

    function a_action() {
        $this->initMemcache();
        echo $this->memcache->get('a');
    }

    function b_action() {
        $this->getGets($key);
        $this->getPosts($key);
        //读取LIB的接口
        $this->loadLib('links/lsms');
        $this->lsms->buildRC(1, 1);
    }

    function m_action() {
        //读取mod的接口
        $this->loadModule('links/mdemo');


        $parm = array();
        $parm['mobile'] = '18688931217';
//        $rc = $this->mdemo->getUserInfo($parm);
//        var_dump($rc);

        $rc = $this->mdemo->test($parm);
        var_dump($rc);

         //insert demo
         //$parm2 = array();
         //$parm2['mobile'] = '11111111';
        //$parm2['pw'] = '111';
        //$parm2['role'] = '1';
        //$parm2['status'] = '1';
        //$rc = $this->mdemo->insert($parm2);


        //update demo
        //var_dump($rc);
        //$parm2 = array();
        //$parm2['pw'] = '23';
        //$parm2['role'] = '2';
        //$rc = $this->mdemo->update($parm2, "`mobile`='11111111'");
        //var_dump($rc);


        //delete demo
        //$rc = $this->mdemo->delete("`mobile`='11111111'");
        //var_dump($rc);
    }


    function x_action(){
        $x = 0;
        if(empty($x)){          echo '1';
        };
    }
}
