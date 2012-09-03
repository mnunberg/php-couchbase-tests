<?php
require_once 'Common.php';

class Replace extends CouchbaseTestCommon {
    
    # 013
    
    function testReplaceOO() {
        $oo = $this->oo;
        $key = $this->mk_key();
        
        $rv = $oo->replace($key, "bar");
        $this->assertFalse($rv);
        $this->assertEquals(COUCHBASE_KEY_ENOENT,
                            $oo->getResultCode());
        
        $oo->set($key, "foo");
        $rv = $oo->replace($key, "bar");
        $this->assertNotEmpty($rv);
        $val = $oo->get($key);
        $this->assertEquals("bar", $val);
    }
    
    function testReplace() {
        $h = $this->handle;
        $key = $this->mk_key();
        
        $rv = couchbase_replace($h, $key, "bar");
        $this->assertFalse($rv);
        $this->assertEquals(COUCHBASE_KEY_ENOENT,
                            couchbase_get_result_code($h));
        
        couchbase_set($h, $key, "foo");
        $rv = couchbase_replace($h, $key, "bar");
        $this->assertNotEmpty($rv);
        
        $val = couchbase_get($h, $key);
        $this->assertEquals("bar", $val);
    }
}