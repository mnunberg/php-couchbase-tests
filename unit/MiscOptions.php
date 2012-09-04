<?php
require_once 'Common.php';

class MiscOptions extends CouchbaseTestCommon {
    function testMiscOptions() {
        $h = $this->getPersistHandle();
        $oo = $this->getPersistOO();
        
        $msg = NULL;
        try {
            $oo->setOption(1111, 111);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('unknown option', $msg);
        
        $msg = NULL;
        try {
            couchbase_set_option($h, 1111, 111);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('unknown option', $msg);
    }
}