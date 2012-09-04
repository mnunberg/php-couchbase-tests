<?php
require_once 'Common.php';

$values = array();

function global_content_cb($myh, $val) {
    global $values;
    array_push($values, $val);
}

class Delay extends CouchbaseTestCommon {
    
    # 019
    
    # In each of the functions below we retrieve a resultset (either at once
    # or incrementally), and place it inside the 'values' array. This common
    # test checks to see if:
    # 1) All keys in the values array are present within both the $casrets (the
    # array returned by set) and in the $keys array (which is the original data
    # source). If the request also gives us the CAS, then we ensure the CAS indeed
    # matches.
    
    private function _xref_assert_common($casvals,
                                         $keys,
                                         $check_cas = false) {
        global $values;
        $this->assertCount(10, $values);
        
        foreach (array_values($values) as $val) {
            $k = $val['key'];
            $v = $val['value'];
            
            $this->assertNotEmpty($k);
            $this->assertNotEmpty($v);
            
            $this->assertArrayHasKey($k, $keys);
            $this->assertArrayHasKey($k, $casvals);
            
            $this->assertEquals($keys[$k], $v);
            
            if ($check_cas) {
                $c = $val['cas'];
                $this->assertNotEmpty($c);
                $this->assertEquals($casvals[$k], $c);
            }
        }
    }
    
    function testContentCb() {
        
        $h = $this->getPersistHandle();
        global $values;
        
        $keys = $this->makeKvPairs(10);

        $casvals = couchbase_set_multi($h, $keys);
        
        foreach ($keys as $k => $v) {
            $this->assertArrayHasKey($k, $casvals);
        }
        
        $this->assertInternalType('array', $casvals);
        
        couchbase_get_delayed($h, array_keys($keys), false, "global_content_cb");
        
        $this->_xref_assert_common($casvals, $keys, false);
        
        
        $values = array();
        couchbase_get_delayed($h, array_keys($keys), true, "global_content_cb");
        $this->_xref_assert_common($casvals, $keys, true);
        
    }
    
    function testAnonCb() {
        global $values;
        $keys = $this->makeKvPairs(10);
        $h = $this->getPersistHandle();
        
        $values = array();
        $casvals = couchbase_set_multi($h, $keys);
        
        couchbase_get_delayed($h,
                              array_keys($keys),
                              false,
                              function($myh, $val) {
                                global $values;
                                array_push($values, $val);
                              });
        $this->_xref_assert_common($casvals, $keys, false);
    }
    
    function testInvalidCb() {
        global $values;
        $keys = $this->makeKvPairs(10);
        $h = $this->getPersistHandle();
        $values = array();
        $casvals = couchbase_set_multi($h, $keys);
        
        $msg = NULL;
        try {
            couchbase_get_delayed($h, array_keys($keys), false,
                                  "non-exist-callback");
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        
        $this->assertNotNull($msg);
        $this->assertContains('invalid function', $msg);
    }
    
    # 020
    function testFetchAll() {
        $h = $this->getPersistHandle();
        $keys = $this->makeKvPairs(10);
        $casrets = couchbase_set_multi($h, $keys);
        $rv = couchbase_get_delayed($h, array_keys($keys), false);
        
        global $values;
        $values = couchbase_fetch_all($h);
        $this->_xref_assert_common($casrets, $keys, false);
        
        $rv = couchbase_fetch_all($h);
        $this->assertFalse($rv);
        
        $values = array();
        $rv = couchbase_get_delayed($h, array_keys($keys), true);
        $values = couchbase_fetch_all($h);
        $this->_xref_assert_common($casrets, $keys, true);
        $rv = couchbase_fetch_all($h);
        $this->assertFalse($rv);
    }
    
    # 021
    function testFetchOne() {
        global $values;
        $h = $this->getPersistHandle();
        $keys = $this->makeKvPairs(10);
        
        $casrets = couchbase_set_multi($h, $keys);
        
        $rv = couchbase_get_delayed($h, array_keys($keys), false);
        $this->assertTrue($rv);
        
        while ($row = couchbase_fetch($h)) {
            array_push($values, $row);
        }
        
        $this->_xref_assert_common($casrets, $keys, false);
        
        $values = array();
        $rv = couchbase_get_delayed($h, array_keys($keys), true);
        $this->assertTrue($rv);
        
        while ($row = couchbase_fetch($h)) {
            array_push($values, $row);
        }
        
        $this->_xref_assert_common($casrets, $keys, true);
    }
    
}