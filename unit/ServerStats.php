<?php
require_once 'Common.php';
class ServerStats extends CouchbaseTestCommon {
    
    # 012
    function testStats() {
        $h = $this->getPersistHandle();
        $stats = couchbase_get_stats($h);
        $this->assertInternalType('array', $stats);
        $this->assertTrue(count($stats) > 0);
        $this->assertTrue(count(current($stats)) > 10);
    }
}