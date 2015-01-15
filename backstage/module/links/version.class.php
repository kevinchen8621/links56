<?php

class version extends cmodule {
    function __construct($db) {
        parent::__construct($db, 'version');
    }
    function getnewversion($kind) {
        $rc = $this->getList(" kind=".$kind, 0, 1, 'version');
        return $rc[0]['version'];
    }
}