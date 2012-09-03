<?php
require_once 'Common.php';

/* 005 */

function cache_cb($res, $key, &$value) {
    $value = "from_db";
    return true;
}

function another_cache_cb($res, $key, &$value) {
    $value = "from_db";
    return false;
}

class MissCB extends CouchbaseTestCommon {
    
    function testMissCbOO() {
        $key = $this->mk_key();
        $this->oo->set($key, "foo");
        $rv = $this->oo->get($key, "cache_cb", $cas);
        $this->assertNotEmpty($cas);
        $this->assertEquals('foo', $rv);
        
        $this->oo->delete($key);
        $rv = $this->oo->get($key, "cache_cb", $cas);
        $this->assertNull($cas);
        $this->assertEquals("from_db", $rv);
        
        $rv = $this->oo->get($key, "another_cache_cb", $cas);
        $this->assertNull($cas);
        $this->assertNull($rv,
                "Return value is empty because function returned false");
    }
    
    function testMissCb() {
        $key = $this->mk_key();
        $h = $this->handle;
        couchbase_set($h, $key, "foo");
        $rv = couchbase_get($h, $key, "cache_cb", $cas);
        $this->assertNotEmpty($cas);
        $this->assertEquals("foo", $rv, "Got value from cluster and not CB");
        
        couchbase_delete($h, $key);
        $rv = couchbase_get($h, $key, "cache_cb", $cas);
        $this->assertNull($cas, "Cas not returned from callback");
        $this->assertEquals("from_db", $rv);
        
        $rv = couchbase_get($h, $key, "another_cache_cb", $cas);
        $this->assertNull($cas);
        $this->assertNull($rv);
    }
}