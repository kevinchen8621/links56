<?php

class mmmsgconst extends cmodule {

    function __construct($db) {
        parent::__construct($db, 'dmmsgconst');
    }

    function del($id) {
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = ' . $id;
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function delByids($ids) { // 删除多个（单个）
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id IN (' . $ids . ')';
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function getOneMsg($id) { // 获取文本更新的数据
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=' . $id . ' LIMIT 1';
        return $this->db->fetch_first($sql);
    }

    function getOneMsgNew($id) {
        $sql = 'SELECT ext FROM ' . $this->table . ' WHERE id=' . $id . ' AND type=4 LIMIT 1';
        return $this->db->fetch_first($sql);
    }

    function getOneMsgMusic($id) {
        $sql = 'SELECT keywords,title,url,url2,intro FROM ' . $this->table . ' WHERE id=' . $id . ' AND type=2 LIMIT 1';
        return $this->db->fetch_first($sql);
    }

    // ============= for keys
    function updateKeys($mid, $keywords, $matchs, $level = 0) { // 添加和修改关键字
        // 1. 先删了所有
        $sql = 'DELETE FROM dmkeysmap WHERE mid IN (' . $mid . ')';
        $this->db->query($sql);
        // 2. 重新添加
        if (!empty($keywords)) {
            foreach ($keywords as $key => $words) {
                $this->addKeys($mid, $words, isset($matchs[$key]) ? 1 : 0, $level);
            }
        }
    }

    function getKeys($mid) {
        $sql = 'SELECT * FROM dmkeysmap WHERE mid = ' . $mid . ' ORDER BY id ASC';
        return $this->db->select($sql);
    }

    function delKeysByMid($mid) {
        $sql = 'DELETE FROM dmkeysmap WHERE mid = ' . $mid;
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function addKeys($mid, $keywrods, $full = 1, $level = 0) {
        $val = '"' . $keywrods . '", ' . $mid . ', ' . $full . ', ' . $level;
        $sql = 'INSERT INTO dmkeysmap (keywords, mid, full, level) VALUES (' . $val . ')';

        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    // for menus
    function getMenus($id = 0) {
        $sql = 'SELECT * FROM dmmenus WHERE fatherid=' . $id . ' ORDER BY sort';
        $father = $this->db->select($sql);
        if (!empty($father) && ($id == 0)) {
            $count = count($father);
            for ($i = 0; $i < $count; $i++) {
                $father[$i]['children'] = $this->getMenus($father[$i]['id']);
            }
        }
        return $father;
    }

    function clearMenus() { // 清空所有数据
        $sql = 'truncate table dmmenus';
        $this->db->query($sql);
        return true;
    }

    function formatAndSaveMenus($menus) { // 保存新自定义菜单
        $newmenus = array();
        if (!isset($menus['menu']['button']))
            return false;
        foreach ($menus['menu']['button'] as $m) { // 先排序，没有title的不保存
            $onemenus = array();
            $onemenus['name'] = $m['name'];
            $onemenus['visible'] = 1;
            $onemenus['sort'] = 0;

            if (is_array($m['sub_button']) && count($m['sub_button']) > 0) {
                $onemenus['type'] = 0;
                $onemenus['parm'] = '';
                $children = array();
                foreach ($m['sub_button'] as $c) {
                    $child = array();
                    $child['name'] = $c['name'];
                    $child['visible'] = 1;
                    $child['sort'] = 0;
                    if (@$c['type'] == 'view') { // 网页
                        $child['type'] = 2;
                        $child['parm'] = $c['url'];
                    } else { // 关键字
                        $child['type'] = 1;
                        $child['parm'] = $c['key'];
                    }
                    $children[] = $child;
                }
                $onemenus['children'] = $children;
            } else {
                if (@$m['type'] == 'view') { // 网页
                    $onemenus['type'] = 2;
                    $onemenus['parm'] = $m['url'];
                } else { // 关键字
                    $onemenus['type'] = 1;
                    $onemenus['parm'] = $m['key'];
                }
            }
            $newmenus[] = $onemenus;
        }
        $this->clearMenus();
        $this->saveMenus($newmenus);
    }

    function saveMenus($menus, $id = 0) { // 保存新自定义菜单
        foreach ($menus as $m) {
            $data = array('name' => $m['name'], 'fatherid' => $id, 'visible' => $m['visible'], 'sort' => $m['sort'], 'type' => $m['type'], 'parm' => $m['parm']);
            $newid = $this->insert($data, 'dmmenus');
            if (isset($m['children']) && ($id == 0)) {
                $this->saveMenus($m['children'], $newid);
            }
        }
    }

    // web
    public function getWebTypeName($id) {
        switch ($id) {
            case 1: return '内部链接';
            case 2: return '外部链接';
            case 3: return '键权跳转';
        }
        return '出错了';
    }

    public function delWebOne($id) {
        $sql = "DELETE FROM `dmmsgweb` WHERE `id`={$id}";
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    public function delWebMultiple($ids) {
        $sql = "DELETE FROM `dmmsgweb` WHERE id IN ({$ids})";
        $this->db->query($sql);
        return $this->db->affected_rows() > 0;
    }

    function getOneWebMsg($id) {
        $sql = "SELECT * FROM `dmmsgweb` WHERE `id`={$id} LIMIT 1";
        return $this->db->fetch_first($sql);
    }

}
