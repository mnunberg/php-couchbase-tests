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
        /** @todo this shouldl also test for PCBC-115 */
    }

    function testVersion() {
        $h = $this->getPersistOO();
        $versions = $h->getVersion();
        $this->assertInternalType('array', $versions);
        foreach ($versions as $endpoint => $vers) {
            $this->assertNotEmpty($endpoint);
            $this->assertNotEmpty($vers);
        }
    }
}
