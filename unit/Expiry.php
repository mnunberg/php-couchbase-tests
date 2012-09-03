<?php
require_once 'Common.php';
class Expiry extends CouchbaseTestCommon {
    
// The basic one replaces 009, the rest are new.
    
    function testExpirySetOO() {
        $oo = $this->oo;
        $key = $this->mk_key();
        
        $oo->add($key, "foo", 1);
        $this->assertEquals("foo", $oo->get($key));
        sleep(2);
        $this->assertNull($oo->get($key));
    }
    
    function testExpirySetZeroOO() {
        $oo = $this->oo;
        $key = $this->mk_key();
        $oo->add($key, "foo", 0);
        sleep(2);
        $this->assertEquals("foo", $oo->get($key));
    }
    
    function testExpiryTouch() {
        $this->markTestSkipped("Touch not implemented");
        $oo = $this->oo;
        $key = $this->mk_key();
        $oo->set($key, "foo");
        $oo->touch($key, 1);
        
        $this->assertEquals("foo",
                            $oo->get($key),
                            "Key exists");
        sleep(2);
        $this->assertNull($o->get($key));
    }
    
    function textExpiryTouchZero() {
        $this->markTestSkipped("Touch not implemented");
        $oo = $this->oo;
        $key = $this->mk_key();
        $oo->set($key, "foo");
        $oo->touch($key, 0);
        # what happens here?
        sleep(2);
        $this->assertEquals("foo", $oo->get($key));
    }
    
    function testArithmeticExpiry() {
        $this->markTestIncomplete("Waiting for docs");
    }
}