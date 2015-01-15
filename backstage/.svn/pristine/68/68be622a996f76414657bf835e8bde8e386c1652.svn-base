<?php

class printid extends cmodule {
    public function get($type='02'){
        $mon = date('m');
        $year = date('y');
        $x = $year.$mon;
        $this ->lock(true,'lockcount');
        $rc = $this ->getList("`id`='{$x}'",0,1,'lockcount');
        if(empty($rc)){
            $this ->insert(array('id'=>$x,'count_printid'=>1),'lockcount');
            $rc = 1;
        }else{
            $rc = $rc[0]['count_printid'];
        }
        $this ->update(array('count_printid'=>($rc+1)),"`id`='{$x}'",'lockcount');
        $this ->unlock();
        $len = strlen($rc);
        if($len ==1){
            $rc = '000'.$rc;
        }
        if($len ==2){
            $rc = '00'.$rc;
        }
        if($len ==3){
            $rc = '0'.$rc;
        }
        $rc = 'LK'.$x.$type.$rc;
        return $rc;
    }
}