<?php
require_once 'Common.php';

class IntegerArgs extends CouchbaseTestCommon {
    
    // 015
    function testIntKeyArgs() {
        $key = 888888;
        $oo = $this->oo;
        $oo->delete($key);
        
        $oo->get($key);
        $this->assertEquals(COUCHBASE_KEY_ENOENT,
                            $oo->getResultCode());
        
        $oo->replace($key, "foo");
        $this->assertEquals(COUCHBASE_KEY_ENOENT,
                            $oo->getResultCode());
        
        $oo->add($key, "foo");
        $this->assertEquals(COUCHBASE_SUCCESS,
                            $oo->getResultCode());
                
        $oo->delete($key);
        $this->assertEquals(COUCHBASE_SUCCESS,
                            $oo->getResultCode);
        
        $oo->append($key, "_suffix");
        $this->assertEquals(COUCHBASE_NOT_STORED,
                            $oo->getResultCode());
    }
    
    // 029
    function testIntValueArgs() {
        $key = $this->mk_key();
        $oo = $this->oo;
        
        $oo->set($key, 642349292);
        $val = $oo->get($key);
        $this->assertInternalType('int', $val);
        $this->assertEquals(642349292, $val);
        
        # Smaller value? sure..
        $oo->delete($key);
        $oo->set($key, 5);
        $val = $oo->get($key);
        $this->assertInternalType('int', $val);
        $this->assertEquals(5, $val);
    }
}

?>