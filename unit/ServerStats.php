<?php
require_once 'Common.php';
class ServerStats extends CouchbaseTestCommon {
    
    # 012
    /**
     * @test Server Stats
     * @pre Request Server Stats
     * @post The return value is an array. The array contains at least
     * one sub-array. The sub-array has at least 10 items.
     */
    function testStats() {
        $h = $this->getPersistHandle();
        $stats = couchbase_get_stats($h);
        $this->assertInternalType('array', $stats);
        $this->assertTrue(count($stats) > 0);
        $this->assertTrue(count(current($stats)) > 10);
    }
}