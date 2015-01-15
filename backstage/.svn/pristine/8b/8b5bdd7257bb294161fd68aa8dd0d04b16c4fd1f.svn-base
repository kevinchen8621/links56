<?php

class mmmsgweb extends cmodule {
    public function __construct($db) {
        parent::__construct($db, 'dmmsgweb');
    }
//
//    public function getOneMsg($id) {
//        $sql = "SELECT * FROM `dmsgweb` WHERE `id`={$id} LIMIT 1";
//        return $this->db->fetch_first($sql);
//    }
//
//
//
//    public function edit($id, $content) {
//        $sql = "UPDATE `dmsgweb` SET `name`='{$content['name']}', ";
//        $sql .= "`title`='{$content['title']}', `type`={$content['type']}, ";
//        $sql .= "`url`='{$content['url']}', `ts`={$content['ts']} WHERE `id`={$id}";
//        $this->db->query($sql);
//        return $this->db->affected_rows() > 0;
//    }

    public function updateClicks($code) {
        // $this->db->query('LOCK TABLES `dmsgweb` WRITE');
        $sql = "UPDATE `dmmsgweb` SET `clicks`=`clicks`+1 WHERE `code`='{$code}'";
        $this->db->query($sql);
        // $this->db->query('UNLOCK TABLES');
    }



//    public function add($content) {
//        $sql = "INSERT INTO `dmsgweb`(`name`, `title`, `type`, `url`, `code`, `ts`)";
//        $sql .= "VALUES('{$content['name']}','{$content['title']}',";
//        $sql .= "{$content['type']},'{$content['url']}', '{$content['code']}', {$content['ts']})";
//        $this->db->query($sql);
//        return $this->db->affected_rows() > 0;
//    }
}
