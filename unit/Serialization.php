<?php
require_once 'Common.php';
class Serialization extends CouchbaseTestCommon {
    
    
    function _cmp_stdtypes($h, $k, $v, $tname) {
        couchbase_delete($h, $k);
        $rv = couchbase_add($h, $k, $v);
        $this->assertNotEmpty($rv,
                              "Can serialize " . $tname);
        $ret = couchbase_get($h, $k);
        $this->assertEquals($v, $ret, "Can deserialize " . $tname);
    }
    
    # 023    
    function testSerializeBasic() {
        $h = $this->getPersistHandle();
        $value = array(1,2,3);
        $key = $this->mk_key();
        
        $this->_cmp_stdtypes($h, $key, array(1,2,3),
                             "Array");
                
        couchbase_delete($h, $key);
        $value = new stdClass();
        $rv = couchbase_add($h, $key, $value);
        $this->assertNotEmpty($rv,
                              "Can serialize class objects");
        $ret = couchbase_get($h, $key);
        $this->assertInstanceOf('stdClass', $ret,
                                "Can deserialize class objects");
        
        
        $this->_cmp_stdtypes($h, $key, array("dummy"),
                             "Single element array");        
        
        $this->_cmp_stdtypes($h, $key, NULL, "NULL");
        
        $this->_cmp_stdtypes($h, $key, TRUE, "TRUE");
        
        $this->_cmp_stdtypes($h, $key,
                             array(1,2,3, "Test" => "bar", array("dummmy")),
                             "mixed array");
    }
    
    function testSerializeFileError() {
        $this->markTestSkipped("weird-object checking not yet implemented");
        $key = $this->mk_key();
        $fp = fopen(__FILE__, "r");
        $msg = NULL;
        try {
            $rv = couchbase_set($h, $key, $fp);
            $ret = couchbase_get($h, $key);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('cannot serialize', $fp);
    }
    
    # 024
    function testSerializeJsonArrayMixed() {
        if (!extension_loaded('json') ||
            !defined('COUCHBASE_SERIALIZER_JSON_ARRAY')) {
            $this->markTestSkipped("No JSON support");
        }
        
        $h = make_handle();
        couchbase_set_option($h,
                             COUCHBASE_OPT_SERIALIZER,
                             COUCHBASE_SERIALIZER_JSON_ARRAY);
        $key = $this->mk_key();
        
        $value = array(1,2,3, "Test" => "bar", array("dummmy"));
        $this->_cmp_stdtypes($h, $key, $value, "mixed array");
    }
    
    # 024
    function testSerializeJsonObjectMixed() {
        
        if (!extension_loaded('json') ||
            !defined('COUCHBASE_SERIALIZER_JSON')) {
            $this->markTestSkipped("No JSON support");
        }
                
        $h = make_handle();
        couchbase_set_option($h,
                             COUCHBASE_OPT_SERIALIZER,
                             COUCHBASE_SERIALIZER_JSON);
        
        $key = $this->mk_key();
        $value = array(1,2,3, "Test" => "bar", array("dummmy"));
        $rv = couchbase_add($h, $key, $value);
        $this->assertNotEmpty($rv);
        $ret = couchbase_get($h, $key);
        $this->assertInstanceOf('stdClass', $ret,
                                "Return value is an object");
    }
    
    function testMixedSerializationErrors() {
        $h_php = make_handle();
        $h_json = make_handle();
        $value = array(1,2,3);
        $key = $this->mk_key();
        
        couchbase_set_option($h_json,
                             COUCHBASE_OPT_SERIALIZER,
                             COUCHBASE_SERIALIZER_JSON);
        
        couchbase_set_option($h_php,
                             COUCHBASE_OPT_SERIALIZER,
                             COUCHBASE_SERIALIZER_PHP);
        
        $rv = couchbase_add($h_json, $key, $value);
        $this->assertNotEmpty($rv, "Can serialize JSON");
        
        $ret = couchbase_get($h_php, $key);
        
        $this->assertEquals($ret, $value,
                            "Can use different flags for JSON and native PHP");
        
        couchbase_delete($h_json, $key);
        $rv = couchbase_add($h_php, $key, $value);
        $this->assertNotEmpty($rv);
        $ret = couchbase_get($h_json, $key);
        $this->assertEquals($value, $ret);
    }
    
    function testSerializeAppend() {
        $this->markTestIncomplete("Not implemented");
    }
    
    # 024
    function testSerializerOptions() {
        $h = make_handle();
        couchbase_set_option($h, COUCHBASE_OPT_SERIALIZER,
                             COUCHBASE_SERIALIZER_PHP);
        $cur = couchbase_get_option($h, COUCHBASE_OPT_SERIALIZER);
        $this->assertEquals(COUCHBASE_SERIALIZER_PHP, $cur);
        
        $msg = NULL;
        try {
            couchbase_set_option($h, COUCHBASE_OPT_SERIALIZER, 1111);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('unsupported serializer', $msg);
    }
    
    function testSerializerOptionsOO() {
        $oo = make_handle_oo();
        $rv = $oo->setOption(COUCHBASE::OPT_SERIALIZER,
                             COUCHBASE::SERIALIZER_PHP);
        $this->assertTrue((bool)$rv);
        
        $cur = $oo->getOption(COUCHBASE::OPT_SERIALIZER);
        $this->assertEquals(COUCHBASE::SERIALIZER_PHP, $cur);
        
        $msg = NULL;
        try {
            $oo->setOption(COUCHBASE::OPT_SERIALIZER, 1111);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('unsupported serializer', $msg);
    }
}