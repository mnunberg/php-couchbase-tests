<?php
require_once 'Common.php';

class GetMulti extends CouchbaseTestCommon
{
    
    function makeKvPairs($count = 10) {
        $ret = array();
        for ($ii = 0; $ii < $count; $ii++) {
            $k = $this->mk_key();
            $v = uniqid("couchbase_value_");
            $ret[$k] = $v;
        }
        return $ret;
    }
    
    function _test_common_oo() {
        $oo = $this->oo;
        $keys = array();
        $cas = array();
        $keys = $this->makeKvPairs(10);
        
        foreach ($keys as $k => $v) {
            $cas[$k] = $oo->set($k, $v);
        }
        
        $values = $oo->getMulti(array_keys($keys), $cas1);
        
        
        sort($values);
        sort($keys);
        sort($cas1);
        sort($cas);
        
        $this->assertEquals(count($values), count($keys));
        $this->assertEquals(serialize($values), serialize($keys));
        $this->assertEquals(serialize($cas), serialize($cas1));
        
        $k = array_keys($keys);
        $this->assertEquals($values[$k[0]], $keys[$k[0]]);
    }
    
    function _test_common() {
        $h = $this->handle;
        $keys = array();
        $cas = array();
        
        $keys = $this->makeKvPairs(10);
        
        foreach ($keys as $k => $v) {
            $cas[$k] = couchbase_set($h, $k, $v);
        }
        $values = couchbase_get_multi($h, array_keys($keys), $cas1);
        sort($values);
        sort($keys);
        sort($cas1);
        sort($cas);
        
        $this->assertEquals(count($values), count($keys));
        $this->assertEquals(serialize($values), serialize($keys));
        $this->assertEquals(serialize($cas), serialize($cas1));
    }
    
    # Replaces 007
    function testPlainOO() {
        $this->_test_common_oo();
        #prefixed:
        $this->oo->setOption(COUCHBASE_OPT_PREFIX_KEY, "foo_");
        $this->_test_common_oo();
        $this->oo->setOption(COUCHBASE_OPT_PREFIX_KEY, '');
    }
    
    function testPlain() {
        $this->_test_common();
    }
    
    # test setMulti on its own, replaces 017
    function testSetMulti() {
        $h = $this->handle;
        $values = $this->makeKvPairs(10);
        
        $casrets = couchbase_set_multi($h, $values, 1);
        $this->assertCount(10, $casrets);
        
        foreach ($casrets as $k => $cas) {
            $this->assertArrayHasKey($k, $values);
        }
        
        foreach ($values as $k => $val) {
            $this->assertArrayHasKey($k, $casrets);
            couchbase_delete($h, $k);
        }
    }
    
    function testSetMultiOO() {
        $oo = $this->oo;
        $values = $this->makeKvPairs(10);
        $casrets = $this->oo->setMulti($values, 1);
        
        $this->assertCount(10, $casrets);
        
        
        # Cross-checking the arrays, PCBC-66
        foreach ($casrets as $k => $cas) {
            $this->assertArrayHasKey($k, $values);
        }
        
        foreach ($values as $k => $val) {
            $this->assertArrayHasKey($k, $casrets);
            $oo->delete($k);
        }
    }
    
    # 032
    function testMgetOrdered() {
        # ensure keys are in order, if requested..
        
        $oo = $this->getPersistOO();
        $values = $this->makeKvPairs(10);
        $casrets = $oo->setMulti($values, 1);
        asort($values);
        
        $keys = array_keys($values);
        $res = $oo->getMulti($keys, $cas, Couchbase::GET_PRESERVE_ORDER);
        $this->assertEquals(serialize($values), serialize($res));
    }
    
}