<?php

/* This file replaces 010 */

require_once 'Common.php';
class Arithmetic extends CouchbaseTestCommon
{
    function testIncrDecr() {
        $oo = $this->oo;
        $key = $this->mk_key();
        $value = "2";
        
        $oo->add($key, $value);
        $rv = $oo->increment($key);
        $this->assertInternalType('int', $rv,
                                  "Incr return is numeric");
        $this->assertEquals(3, $rv);
        
        $rv = $oo->get($key);
        $this->assertInternalType('string', $rv,
                                  "Get return is string");
        $this->assertEquals('3', $rv);
        
        $rv = $oo->decrement($key);
        $this->assertInternalType('int', $rv,
                                  "Decr return is numeric");
        $this->assertEquals(2, $rv);
        
        $rv = $oo->get($key);
        $this->assertInternalType('string', $rv);
        $this->assertEquals('2', $rv);        
    }
    
    function testIncrString() {
        $key = $this->mk_key();
        $oo = $this->oo;
        $oo->set($key, "String");
        
        $msg = NULL;
        try {
            $rv = $oo->increment($key);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        
        $this->assertNotNull($msg, "Got exception for incrementing a string");
        $this->assertContains("Not a number", $msg);
    }
    
    function testIncrNonexist() {
        $key = $this->mk_key();
        $oo = $this->oo;
        
        $rv = $oo->increment($key,
                             $offset = 1,
                             $create = true,
                             $expire = NULL,
                             $initial_value = 2);
        
        $this->assertEquals(2, $rv,
                            "Value is set to the default rather than the offset");
        $rv = $oo->get($key);
        $this->assertEquals('2', $rv);
    }

}

?>