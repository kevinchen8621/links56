<?php

class ccreatedb_controller extends cbase {

    function getName() {
        return 'ccreatedb';
    }

    function index_action() {
        $this->loadModule();
        $this->province(); // 省份参数表
        $this->city(); // 市参数表
        $this->district(); // 区县参数表
        $this->bank(); // 银行参数表
        $this->truck_type(); // 车型参数表
        $this->truck_length(); // 车型参数表
        $this->truck_load(); // 吨位参数表
        $this->truck_body(); // 货厢参数表
        $this->truck_special(); // 特种车参数表
        $this->truck_brand(); // 货车品牌参数表

        $this->trucker_template(); // 司机系统提醒模板表
        $this->shipper_template(); // 货主（操作员）系统提醒模板表

        $this->account(); // 帐号
        $this->links_staff(); // 邻客人员详情
        $this->shipper(); // 货主企业信息


        echo 'ok: ', date('Y-m-d H:i:s');
    }

    private function shipper() { // 货主企业信息
        $sql = 'CREATE TABLE IF NOT EXISTS `shipper` (';
        $sql .= '`shipperid` int(11) NOT NULL AUTO_INCREMENT,';
        $sql .= '`status` int(11) NOT NULL DEFAULT "0" COMMENT "认证状态",';
        $sql .= '`company` varchar(128) NOT NULL COMMENT "公司名称",';
        $sql .= '`short_name` varchar(32) COMMENT "公司名称缩写",';
        
        $sql .= '`province` varchar(32) NOT NULL COMMENT "省",';
        $sql .= '`city` varchar(32) NOT NULL COMMENT "市",';
        $sql .= '`district` varchar(32) NOT NULL COMMENT "区",';
        $sql .= '`provinceid` int(11) NOT NULL COMMENT "省id",';
        $sql .= '`cityid` int(11) NOT NULL COMMENT "市id",';
        $sql .= '`districtid` int(11) NOT NULL COMMENT "区id",';
        
        $sql .= '`address` varchar(256) NOT NULL COMMENT "公司详细地址",';
        $sql .= '`tel` varchar(32) NOT NULL COMMENT "公司联系电话",';
        $sql .= '`website` varchar(512) NOT NULL COMMENT "网址",';
        $sql .= '`business` varchar(256) NOT NULL COMMENT "主营业务",';
        
        
        
        $sql .= '`role` int(11) NOT NULL COMMENT "角色名称",';
        $sql .= '`last_signin_time` timestamp COMMENT "上次登录时间",';
        $sql .= '`current_signin_time` timestamp COMMENT "当前登录时间",';
        $sql .= '`register_time` timestamp NOT NULL COMMENT "注册时间",';
        $sql .= '`message_number` int(11) NOT NULL DEFAULT "0" COMMENT "未读消息数目",';
        $sql .= '`note` varchar(256) DEFAULT "" COMMENT "备注",';
        $sql .= 'PRIMARY KEY (`shipperid`),';
        $sql .= 'KEY `mobile` (`mobile`),';
        $sql .= 'KEY `wechatid` (`wechatid`)';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT="货主企业信息" AUTO_INCREMENT=1 ;';

        $this->cmodule->query($sql);
    }

    private function links_staff() { // 邻客人员详情
        $sql = 'CREATE TABLE IF NOT EXISTS `links_staff` (';
        $sql .= '`accountid` int(11) NOT NULL COMMENT "帐号id",';
        $sql .= '`name` varchar(64) NOT NULL COMMENT "名字",';
        $sql .= '`department` varchar(64) NOT NULL COMMENT "部门",';
        $sql .= '`note` varchar(256) DEFAULT "" COMMENT "备注",';
        $sql .= 'PRIMARY KEY (`accountid`)';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT="邻客人员详情";';

        $this->cmodule->query($sql);
    }

    private function account() { // 帐号
        $sql = 'CREATE TABLE IF NOT EXISTS `account` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT,';
        $sql .= '`mobile` varchar(32) NOT NULL COMMENT "手机",';
        $sql .= '`pw` varchar(32) NOT NULL COMMENT "密码md5",';
        $sql .= '`wechatid` varchar(32) NOT NULL COMMENT "微信id",';
        $sql .= '`role` int(11) NOT NULL COMMENT "角色名称",';
        $sql .= '`status` int(11) NOT NULL DEFAULT "1" COMMENT "状态",';
        $sql .= '`last_signin_time` timestamp COMMENT "上次登录时间",';
        $sql .= '`current_signin_time` timestamp COMMENT "当前登录时间",';
        $sql .= '`register_time` timestamp NOT NULL COMMENT "注册时间",';
        $sql .= '`message_number` int(11) NOT NULL DEFAULT "0" COMMENT "未读消息数目",';
        $sql .= '`note` varchar(256) DEFAULT "" COMMENT "备注",';
        $sql .= 'PRIMARY KEY (`id`),';
        $sql .= 'KEY `mobile` (`mobile`),';
        $sql .= 'KEY `wechatid` (`wechatid`)';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT="帐号" AUTO_INCREMENT=1 ;';

        $this->cmodule->query($sql);
    }

    private function trucker_template() { // 司机系统提醒模板表
        $sql = 'CREATE TABLE IF NOT EXISTS `trucker_template` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT,';
        $sql .= '`name` varchar(64) NOT NULL COMMENT "模板名字",';
        $sql .= '`template` varchar(1024) NOT NULL COMMENT "系统提醒模板",';
        $sql .= '`update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "更新时间",';
        $sql .= '`update_accountid` int(11) NOT NULL DEFAULT "11" COMMENT "更新帐号",';
        $sql .= 'PRIMARY KEY (`id`),';
        $sql .= 'KEY `name` (`name`)';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "司机系统提醒模板表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function shipper_template() { // 货主（操作员）系统提醒模板表
        $sql = 'CREATE TABLE IF NOT EXISTS `shipper_template` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT,';
        $sql .= '`name` varchar(64) NOT NULL COMMENT "模板名字",';
        $sql .= '`template` varchar(1024) NOT NULL COMMENT "系统提醒模板",';
        $sql .= '`update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "更新时间",';
        $sql .= '`update_accountid` int(11) NOT NULL DEFAULT "11" COMMENT "更新帐号",';
        $sql .= 'PRIMARY KEY (`id`),';
        $sql .= 'KEY `name` (`name`)';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "货主（操作员）系统提醒模板表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function truck_brand() { // 货车品牌参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `truck_brand` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`truck_brand` varchar(128) NOT NULL COMMENT "货车品牌",';
        $sql .= 'PRIMARY KEY (`id`) ';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "货车品牌参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function truck_special() { // 特种车参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `truck_special` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`truck_special` varchar(128) NOT NULL COMMENT "特种车类型",';
        $sql .= 'PRIMARY KEY (`id`) ';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "特种车参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function truck_body() { // 货厢参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `truck_body` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`truck_body` varchar(128) NOT NULL COMMENT "货厢",';
        $sql .= 'PRIMARY KEY (`id`) ';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "货厢参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function truck_load() { // 吨位参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `truck_load` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`truck_load` varchar(128) NOT NULL COMMENT "吨位",';
        $sql .= 'PRIMARY KEY (`id`) ';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "吨位参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function truck_length() { // 车长参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `truck_length` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`truck_length` varchar(128) NOT NULL COMMENT "车长",';
        $sql .= 'PRIMARY KEY (`id`) ';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "车长参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function truck_type() { // 车型参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `truck_type` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`truck_type` varchar(128) NOT NULL COMMENT "车型",';
        $sql .= 'PRIMARY KEY (`id`) ';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "车型参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function bank() { // 银行参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `bank` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`bank_name` varchar(128) NOT NULL COMMENT "银行全称",';
        $sql .= '`short_name` varchar(32) NOT NULL COMMENT "中文缩写", ';
        $sql .= '`short_pinyin` varchar(32) NOT NULL COMMENT "拼音缩写",';
        $sql .= 'PRIMARY KEY (`id`), ';
        $sql .= 'KEY `bank_name` (`bank_name`),';
        $sql .= 'KEY `short_name` (`short_name`),';
        $sql .= 'KEY `short_pinyin` (`short_pinyin`)';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "银行参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function district() { // 区县参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `district` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` varchar(32) NOT NULL COMMENT "省的中文名",';
        $sql .= '`pinyin` varchar(128) NOT NULL COMMENT "城市的中文名", ';
        $sql .= '`short_pinyin` varchar(32) NOT NULL COMMENT "拼音缩写",';
        $sql .= '`cityid` int(11) NOT NULL COMMENT "对应city表",';
        $sql .= '`provinceid` int(11) NOT NULL COMMENT "对应province表",';
        $sql .= 'PRIMARY KEY (`id`), ';
        $sql .= 'KEY `name` (`name`),';
        $sql .= 'KEY `short_pinyin` (`short_pinyin`),';
        $sql .= 'KEY `cityid` (`cityid`),';
        $sql .= 'KEY `provinceid` (`provinceid`)';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "区县参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function city() { // 市参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `city` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` varchar(32) NOT NULL COMMENT "省的中文名",';
        $sql .= '`pinyin` varchar(128) NOT NULL COMMENT "城市的中文名", ';
        $sql .= '`short_pinyin` varchar(32) NOT NULL COMMENT "拼音缩写",';
        $sql .= '`provinceid` int(11) NOT NULL COMMENT "对应province表",';
        $sql .= '`child_num` int(11) NOT NULL COMMENT "有多少个区",';
        $sql .= 'PRIMARY KEY (`id`), ';
        $sql .= 'KEY `name` (`name`),';
        $sql .= 'KEY `short_pinyin` (`short_pinyin`),';
        $sql .= 'KEY `provinceid` (`provinceid`)';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "市参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function province() { // 省份参数表
        $sql = 'CREATE TABLE IF NOT EXISTS `province` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` varchar(32) NOT NULL COMMENT "省的中文名",';
        $sql .= '`pinyin` varchar(128) NOT NULL COMMENT "拼音全称", ';
        $sql .= '`short_pinyin` varchar(32) NOT NULL COMMENT "拼音缩写",';
        $sql .= 'PRIMARY KEY (`id`), ';
        $sql .= 'KEY `name` (`name`),';
        $sql .= 'KEY `short_pinyin` (`short_pinyin`)';
        $sql .= ') ENGINE = InnoDB DEFAULT CHARSET = utf8 ROW_FORMAT = DYNAMIC COMMENT = "省份参数表" AUTO_INCREMENT = 1; ';

        $this->cmodule->query($sql);
    }

    private function template() { // 模板
        $sql = 'CREATE TABLE IF NOT EXISTS `province` (';
        $sql .= '`aa` int(11) NOT NULL AUTO_INCREMENT COMMENT "aa",';
        $sql .= '`ba` tinyint(4) NOT NULL COMMENT "bb",';
        $sql .= '`ca` varchar(1234) NOT NULL COMMENT "cc",';
        $sql .= '`da` text NOT NULL COMMENT "dd",';
        $sql .= '`ea` timestamp NULL DEFAULT NULL COMMENT "ee",';
        $sql .= '`fa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "ff",';
        $sql .= '`ga` int(11) NOT NULL DEFAULT "11" COMMENT "gg",';
        $sql .= '`ha` int(11) DEFAULT NULL COMMENT "hh",';
        $sql .= 'PRIMARY KEY (`aa`),';
        $sql .= 'KEY `ba` (`ba`),';
        $sql .= 'KEY `ga` (`ga`)';
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT="省份参数表" AUTO_INCREMENT=1 ;';

        $this->cmodule->query($sql);
    }

    function cb_action() {
        // hlog::logDebug('faace7', var_export($_GET, true));
    }

}
