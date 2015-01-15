<?php

class mmadmin extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'dmadmin');
    }

    function checkInfo($parm) { // 检查openid，用户名和密码是否合法
        $openid = $parm['openid'];
        $name = $parm['name'];
        $pw = $parm['pw'];

        if (!empty($name) && !empty($pw)) { // 第一次时用登录名和密码登录
            $info['name'] = $name;
            $info['pw'] = $pw;
            $dmadmin = $this->getInfoP($info);
            if (empty($dmadmin))
                return false;
            if (empty($dmadmin['openid'])) {
                return $this->updateOpenid($parm);
            }
            return ($dmadmin['openid'] == $openid);
        }
        $info2['openid'] = $openid;
        $dmadmin = $this->getInfoP($info2);
        return !empty($dmadmin);
    }

    public function updateOpenid($parm) { // faace 关联openid和用户名
        $sql = "UPDATE dmadmin SET openid='{$parm['openid']}' WHERE name='{$parm['name']}' AND pw='{$parm['pw']}'";
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    public function getInfoP($parm) {
        if (isset($parm['id'])) {
            $sql = "SELECT * FROM `dmadmin` WHERE `id`='{$parm['id']}' LIMIT 1";
        } else if (isset($parm['name']) && isset($parm['pw'])) {
            $sql = "SELECT * FROM dmadmin WHERE name='{$parm['name']}' AND pw='{$parm['pw']}' LIMIT 1";
        } else if (isset($parm['openid'])) { // 顺序不要替换，优先使用mane和pw
            $sql = "SELECT * FROM `dmadmin` WHERE `openid`='{$parm['openid']}' LIMIT 1";
        } else if (isset($parm['asc'])) {
            $sql = "SELECT * FROM `dmadmin` WHERE `name`='{$parm['asc']}' AND id > 2999 LIMIT 1";
        } else {
            return false;
        }
        return $this->db->fetch_first($sql);
    }

    function updateUserInfo($openid, $nickname, $picurl) {
        $sql = "UPDATE `dmadmin` SET nickname='{$nickname}', picurl='{$picurl}' WHERE openid='{$openid}'";
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function editP($parm) {
        if ($parm['id'] == '0') {
            return $this->insert($parm['info']);
        } else {
            return $this->update($parm['info'], '`id`=' . $parm['id']);
        }
    }

    public function getMyGroupP($parm) { // 获取可以配置的所有群组
        $gid = $parm['gid'];
        $sql = "SELECT * FROM `dmgroup` WHERE (`id`='{$gid}' OR `fatherid`='{$gid}') AND type=0 ORDER BY name";
        return $this->db->select($sql);
    }

    public function getGroupP($parm) { // 获取可以配置的所有群组
        $id = $parm['id'];
        $sql = "SELECT * FROM `dmgroup` WHERE `id`='{$id}' LIMIT 1";
        return $this->db->fetch_first($sql);
    }

    function groupEditP($parm) {
        if ($parm['id'] == '0') {
            return $this->insert($parm['info'], 'dmgroup');
        } else {
            return $this->update($parm['info'], '`id`=' . $parm['id'], 'dmgroup');
        }
    }

    public function getGroupStatusList($parm = array()) { // 获取可以配置的所有群组
        if (isset($parm['all']))
            return array(
                '0' => '正常',
                '1' => '暂停',
                '0' => '删除'
            );
        return array(
            '0' => '正常',
            '1' => '暂停'
        );
    }

    function getGroupType($type) { // 获取群组状态
        switch ($type) {
            case 0:
                return '正常';
            case 1:
                return '暂停';
            case 2:
                return '删除';
        }
        return '非法';
    }

}
