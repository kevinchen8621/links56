<?php

class hwuserinfo extends hbase {

    function __construct($db) {
        parent::__construct($db);
        $this->loadDB('diusers');
    }

    function updateUserInfo($parm) {
        if (!empty($parm['openid'])) {
            $openid = $parm['openid'];
            $ui = $this->runWXLib('wuserinfo', 'getInfoP', $parm);
            if (!empty($ui)) {
                if (isset($ui['headimgurl'])) {
                    $user['headimgurl'] = $ui['headimgurl'];
                    $user['nickname'] = $ui['nickname'];
                    $user['province'] = $ui['province'];
                    $user['city'] = $ui['city'];
                    $user['sex'] = intval($ui['sex']);
                    $user['actts'] = date('Y-m-d H:i:s');
                    foreach ($user as $key => $d) {
                        if (empty($d)) {
                            unset($user[$key]);
                        }
                    }
                    $this->diusers->update($user, 'openid="' . $openid . '"');
                } else {
                    $use['actts'] = date('Y-m-d H:i:s');
                    $this->diusers->update($use, 'openid="' . $openid . '"');
                }
            }
        }
    }

    function getUserinfoP($parm) { // 只能用在已经关注的用户上
        if (!empty($parm['openid'])) {
            $openid = $parm['openid'];
//return $openid;
            $user = $this->diusers->getInfo($openid);
//  return $user;
            if (!empty($user)) { // 如果有内容，则获取
                if (empty($user['nickname']) || empty($user['headimgurl'])) { // 先获取用户数据
                    $ui = $this->runWXLib('wuserinfo', 'getInfoP', $parm);
                    if (!empty($ui) && isset($ui['headimgurl'])) {
                        $user['headimgurl'] = $ui['headimgurl'];
                        $user['nickname'] = $ui['nickname'];
                        $user['province'] = $ui['province'];
                        $user['city'] = $ui['city'];
                        $user['sex'] = intval($ui['sex']);
                        foreach ($user as $key => $d) {
                            if (empty($d)) {
                                unset($user[$key]);
                            }
                        }
                        $this->diusers->update($user, 'openid="' . $openid . '"');
                        return $user;
                    }
                } else {
                    return $user;
                }
            } else { // 添加一个数据
                $data = array();
                $data['openid'] = $openid;
                $data['subscribe'] = 0;
                $data['qrscene'] = '';
                $this->runDB('diusers', 'insert', $data);
                $ui = $this->runWXLib('wuserinfo', 'getInfoP', $parm);
                if (!empty($ui) && isset($ui['headimgurl'])) {
                    $user['headimgurl'] = $ui['headimgurl'];
                    $user['nickname'] = $ui['nickname'];
                    $user['province'] = $ui['province'];
                    $user['city'] = $ui['city'];
                    $user['sex'] = intval($ui['sex']);
                    foreach ($user as $key => $d) {
                        if (empty($d)) {
                            unset($user[$key]);
                        }
                    }
                    $this->diusers->update($user, 'openid="' . $openid . '"');
                    return $user;
                }
            }
        }
        return false;
    }

}
