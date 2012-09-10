<?php
require_once 'Common.php';

class Replace extends CouchbaseTestCommon {
    
    # 013
    
    /**
     * @test Replace
     * 
     * @pre
     * Use replace to modify a non existent key.
     * Set the key (via set), then replace it again
     * 
     * @post
     * First replace fails.
     * Second replace succeeds (with valid CAS return value)
     *
     * @remark
     * Variants: OO
     */
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