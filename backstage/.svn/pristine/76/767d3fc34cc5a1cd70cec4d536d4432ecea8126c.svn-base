<?php

class mmwechat extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'dmusers');
    }

    function getInfo($openid) {
        $sql = 'SELECT * FROM dmusers WHERE openid="' . $openid . '" LIMIT 1';
        //  return $sql;
        return $this->db->fetch_first($sql);
    }

    function getInfoP($parm) {
        return $this->getInfo($parm['openid']);
    }

    function setSub($openid, $sub = 1) {
        $sql = 'UPDATE dmusers SET subscribe ="' . $sub . '" WHERE openid="' . $openid . '"';
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function setSubP($parm) {
        return $this->setSub($parm['openid'], $parm['sub']);
    }

    function updateUserInfo($openid, $nickname, $headimgurl) {
        $sql = "UPDATE `dmusers` SET nickname='{$nickname}', headimgurl='{$headimgurl}' WHERE openid='{$openid}'";
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function getConstMsgP($parm) { // 获取消息
        $kw = $parm['kw'];
        $mid = $this->getMid($kw);
        if (empty($mid)) { // 全匹配找不到就找模糊匹配的
            $mid = $this->getMid($kw, false);
        }
        if (empty($mid)) { // 还是找不到就直接退出，表示不是关键字
            return false;
        }

        // 根据id获取对应的消息
        $msg = $this->getOneMsg($mid);
        if (empty($msg)) {
            return false;
        }

        if ($msg['type'] == 4) { // 图文消息；
            $news = array();
            $news[] = $msg;
            if (!empty($msg['ext'])) {
                $news = $this->getNews($msg['ext']);
            }
            $msg = array('type' => 4, 'news' => $news);
        }
        return $msg;
    }

    private function getOneMsg($id) { // 获取一条消息
        $sql = 'SELECT * FROM dmmsgconst WHERE id=' . $id . ' LIMIT 1';
        return $this->db->fetch_first($sql);
    }

    private function getNews($ids) { // 获取多条消息
        $sql = 'SELECT * FROM dmmsgconst WHERE id IN(' . $ids . ') LIMIT 9';
        return $this->db->select($sql);
    }

    private function getMid($kw, $full = true, $all = false) { // 获取对应消息的id
        if ($full) { // 全匹配
            $sql = 'SELECT mid FROM dmkeysmap WHERE keywords="' . $kw . '" AND full = 1 LIMIT 1';

            $data = $this->db->fetch_first($sql);
            return isset($data['mid']) ? $data['mid'] : false;
        }
        // 模糊匹配
        $sql = 'SELECT mid FROM dmkeysmap WHERE LOCATE(keywords, "' . $kw . '") > 0 AND full != 1';
        if (!$all) {
            $sql .= ' LIMIT 1';
            $data = $this->db->fetch_first($sql);
            return isset($data['mid']) ? $data['mid'] : false;
        }
        return $this->db->select($sql);
    }

    // =========================================

    function getSexName($sex) {
        return (intval($sex) == 1) ? '男' : '女';
    }

    function getNeedupdateUsers($num) { // 获取一个月没更新的用户数据
        if ($num < 1) {
            $num = 1;
        }
        $date = date('Y-m-d H:i:s', strtotime('-30 days'));
        return $this->getMsg('actts < "' . $date . '"', 0, $num);
    }

}
