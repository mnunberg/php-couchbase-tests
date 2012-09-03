<?php
require_once 'Common.php';

class GetMulti extends CouchbaseTestCommon
{
    function _test_common_oo() {
        $oo = $this->oo;
        $keys = array();
        $cas = array();
        for ($ii = 0; $ii < 10; $ii++) {
            array_push($keys, $this->mk_key());
        }
        
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
        for ($ii = 0; $ii < 10; $ii++) {
            array_push($keys, $this->mk_key());
        }
        
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
        $values = array();
        for ($ii = 0; $ii < 10; $ii++) {
            array_push($values, $this->mk_key());
        }
        
        $casrets = couchbase_set_multi($h, $values, 1);
        $this->assertCount(10, $casrets);
        
        foreach ($casrets as $k => $cas) {
            $this->assertNotEmpty($cas);
            couchbase_delete($h, $k);
        }
        
        $casrets = $this->oo->setMulti($values, 1);
        $this->assertCount(10, $casrets);
        foreach ($casrets as $k => $cas) {
            couchbase_delete($h, $k);
        }
    }
    
}