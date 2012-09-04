<?php
require_once 'Common.php';

# 026
class Prefix extends CouchbaseTestCommon {
    function testPrefix() {
        $h = make_handle();
        $key = $this->mk_key();
        couchbase_set_option($h, COUCHBASE_OPT_PREFIX_KEY, '');
        $pfix = couchbase_get_option($h, COUCHBASE_OPT_PREFIX_KEY);
        $this->assertEquals('', $pfix);
        
        couchbase_set_option($h, COUCHBASE_OPT_PREFIX_KEY, 'prefix');
        $pfix = couchbase_get_option($h, COUCHBASE_OPT_PREFIX_KEY);
        $this->assertEquals('prefix', $pfix);
        
        couchbase_delete($h, $key);
        couchbase_add($h, $key, "dummy");
        
        couchbase_set_option($h, COUCHBASE_OPT_PREFIX_KEY, 'prefix_1');
        # we should really delete keys here.. but I don't foresee duplicates..
        $rv = couchbase_add($h, $key, "foo");
        $this->assertNotEmpty($rv);
        $ret = couchbase_get($h, $key);
        $this->assertEquals('foo', $ret);
        
        couchbase_set_option($h, COUCHBASE_OPT_PREFIX_KEY, 'prefix');
        $ret = couchbase_get($h, $key);
        $this->assertEquals('dummy', $ret);
        
        couchbase_set_option($h, COUCHBASE_OPT_PREFIX_KEY, 'prefix_1');
        $cas = couchbase_prepend($h, $key, 'prefix');
        $ret = couchbase_get($h, $key);
        $this->assertEquals('prefixfoo', $ret);
        
        couchbase_cas($h, $cas, $key, 'foo');
        $ret = couchbase_get($h, $key);
        $this->assertEquals('foo', $ret);
        
        $contents = array (
            $key => "dummy",
        );
        couchbase_set_multi($h, $contents);
        $ret = couchbase_get_multi($h, array_keys($contents));
        $this->assertArrayHasKey($key, $ret);
        $this->assertEquals($ret[$key], $contents[$key]);
        
        $GLOBALS['delayed_key'] = NULL;
        $delaycb = function($res, $value) {
            $GLOBALS['delayed_key'] = $value['key'];
        };
        
        couchbase_get_delayed($h, array_keys($contents), false, $delaycb);
        $this->assertNotNull($GLOBALS['delayed_key']);
        $this->assertEquals($key, $GLOBALS['delayed_key']);
        unset($GLOBALS['delayed_key']);
    }
    
    function testInvalidPrefix() {
        $this->markTestSkipped("Errneously allows object prefixes");
        $h = make_handle();
        @couchbase_set_option($h, COUCHBASE_OPT_PREFIX_KEY, array());
        $pfix = couchbase_get_option($h, COUCHBASE_OPT_PREFIX_KEY);
        $this->assertNull($pfix);
    }
}