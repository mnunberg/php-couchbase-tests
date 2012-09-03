<?php

/* This file replaces 010 and 022 */

require_once 'Common.php';
class Arithmetic extends CouchbaseTestCommon
{
    function testIncrDecrOO() {
        $oo = $this->getPersistOO();
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
    
    function testIncrDecr() {
        $h = $this->getPersistHandle();
        $key = $this->mk_key();
        $value = "2";
        couchbase_add($h, $key, $value);
        $rv = couchbase_increment($h, $key);
        $this->assertInternalType('int', $rv);
        $this->assertEquals(3, $rv);
        
        $rv = couchbase_get($h, $key);
        $this->assertInternalType('string', $rv);
        $this->assertEquals('3', $rv);
        
        $rv = couchbase_decrement($h, $key);
        $this->assertInternalType('int', $rv);
        $this->assertEquals(2, $rv);
        
        $rv = couchbase_get($h, $key);
        $this->assertInternalType('string', $rv);
        $this->assertEquals('2', $rv);
    }
    
    function testIncrStringOO() {
        $key = $this->mk_key();
        $oo = $this->getPersistOO();
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
    
    function testIncrString() {
        $key = $this->mk_key();
        $h = $this->getPersistHandle();
        couchbase_set($h, $key, 'String');
        
        $msg = NULL;
        try {
            $rv = couchbase_increment($h, $key);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('Not a number', $msg);
    }
    
    function testIncrDecrNonexistOO() {
        $key = $this->mk_key();
        $oo = $this->getPersistOO();
        
        $rv = $oo->increment($key,
                             $offset = 1,
                             $create = true,
                             $expire = NULL,
                             $initial_value = 2);
        
        $this->assertEquals(2, $rv,
                            "Value is set to the default rather than the offset");
        $rv = $oo->get($key);
        $this->assertEquals('2', $rv);
        
        $oo->delete($key);
        
        $msg = NULL;
        try {
            $oo->decrement($key, 2);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('No such key', $msg);
    }
    
    function testIncrDecrNonexist() {
        $key = $this->mk_key();
        $h = $this->getPersistHandle();
        $rv = couchbase_increment($h,
                                  $key,
                                  $offset = 1,
                                  $create = true,
                                  $expire = NULL,
                                  $initial_value = 2);
        $this->assertEquals(2, $rv);
        
        $rv = couchbase_get($h, $key);
        $this->assertEquals('2', $rv);
        
        couchbase_delete($h, $key);
        
        $msg = NULL;
        try {
            couchbase_decrement($h, $key, 2);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('No such key', $msg);
    }
    
    function testIncrDecrNonexistPositionalOO() {
        $key = $this->mk_key();
        $oo = $this->getPersistOO();
        $rv = $oo->increment($key, 20, 1, 0, 2);
        $this->assertEquals(2, $rv, "Set to initial value (incr)");
        
        $oo->delete($key);
        $rv = $oo->decrement($key, 20, 1, 0, 2);
        $this->assertEquals(2, $rv, "Set to initial value (Decr)");
    }
    
    function testIncrDecrNonexistPositional() {
        $key = $this->mk_key();
        $h = $this->getPersistHandle();
        $rv = couchbase_increment($h, $key, 20, 1, 0, 2);
        $this->assertEquals(2, $rv);
        
        couchbase_delete($h, $key);
        
        $rv = couchbase_decrement($h, $key, 20, 1, 0, 2);
        $this->assertEquals(2, $rv);
    }
}

?>