<?php
require_once 'Common.php';
class Delete extends CouchbaseTestCommon {
        /* superseds 008 */
    function testDeleteOO() {
        $key = $this->mk_key();
        $value = uniqid("couchbase_");
        $oo = $this->oo;
        
        $oo->add($key, $value);
        $this->assertNotEmpty($oo->get($key));
        $oo->delete($key);
        $this->assertNull($oo->get($key));
        
        # Test CAS
        $cas = $oo->add($key, $value);
        $cas2 = $oo->set($key, "bar");
        
        $this->assertNotEmpty($cas2);
        
        $this->assertFalse($oo->delete($key, $cas),
                           "delete Fails on stale cas");
        
        $this->assertEquals($oo->get($key), "bar");
        
        $oo->delete($key, $cas2);
        $this->assertNull($oo->get($key));
    }
    
    function testDelete() {
        $key = $this->mk_key();
        $value = uniqid("couchbase_");
        $h = $this->handle;
        couchbase_add($h, $key, $value);
        $this->assertNotEmpty(couchbase_get($h, $key));
        couchbase_delete($h, $key);
        $this->assertNull(couchbase_get($h, $key));
        
        $cas = couchbase_add($h, $key, $value);
        $cas2 = couchbase_set($h, $key, "bar");
        $this->assertNotEmpty($cas2);
        $this->assertFalse(couchbase_delete($h, $key, $cas));
        $this->assertEquals("bar", couchbase_get($h, $key));
        
        couchbase_delete($h,$key,$cas2);
        $this->assertNull(couchbase_get($h, $key));
    }

}