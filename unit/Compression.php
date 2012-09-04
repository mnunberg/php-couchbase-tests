<?php
require_once 'Common.php';

class Compression extends CouchbaseTestCommon {
    # Ideally we'd want to see the value actually compressed server-side, but
    # since there's no option to disable *de*-compression, there's nothing we
    # can do here.
    
    # 027 (doesn't really do anythinig there)..
    function testCompression() {
        $this->markTestSkipped("Can't find scenario with which to test compression");
        $h = make_handle();
        $hnc = make_handle();
        couchbase_set_option($hnc, COUCHBASE_OPT_COMPRESSION,
                             COUCHBASE::COMPRESSION_NONE);
        
        
        $this->assertTrue((bool)
                          ini_alter("couchbase.compression_threshold",
                                    "10"));
        $this->assertEquals(10, ini_get("couchbase.compression_threshold"));

        # Set a compressed value
        $v = str_repeat("long_value", 100);
        $ol = strlen($v);
        $key = $this->mk_key();
        $rv = couchbase_add($h, $key, $v);
        $this->assertNotEmpty($rv);
        
        $ret = couchbase_get($hnc, $key);
        $this->assertNotNull($ret);
        $this->assertLessThan($ol, strlen($ret));
    }
}