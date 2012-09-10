<?php

require_once 'Common.php';

class MutateBasic extends CouchbaseTestCommon
{
    /**
     * Supersedes 003
     */
    
    /**
     * All tests use new keys which have been previously removed. Thus it is
     * assumed the key does not exist at test entry, unless otherwise noted 
     */
    
    /**
     * @test
     *
     * @pre add a key
     * @post CAS return is not empty
     * @remark
     * Variants: OO
     */
    function testAdd() {
        $key = $this->mk_key();
        $this->assertNotEmpty(
            couchbase_add($this->handle, $key, "foo"),
            "add");
    }
    
    function testAddOO() {
        $key = $this->mk_key();
        $this->assertNotEmpty($this->oo->add($key, "foo"),
                              "add (OO)");
    }
    
    /* supersedes 004 */
    
    /**
     * @test
     * Returned CAS
     *
     * @pre Set the same key value pair twice, save the CAS from both
     * operations.
     *
     * @post
     * CAS From both operations are not equal
     *
     * @test
     * @remark
     * Variants: OO
     *
     * @test Set with CAS
     * @pre Set the same key (from previous test) twice, first with the
     * first CAS received, second with the second CAS received
     *
     * @post First set fails, second set succeeds
     *
     * @remark
     * Variants: OO, and using the @c cas() function ( @ref testCas )
     */
    function testSetOO() {
        $key = $this->mk_key();
        $cas1 = $this->oo->set($key, "bar");
        $cas2 = $this->oo->set($key, "bar");
        
        $this->assertNotEquals($cas1, $cas2,
                               "CAS not equal for both SETs");
        
        $this->assertFalse($this->oo->set($key, "foo", 0, $cas1),
            "SET fails with outdated CAS");
        
        $this->assertEquals($this->oo->get($key), "bar",
            "Value remains");
        
        $this->assertNotEmpty($this->oo->set($key, "foo", 0, $cas2),
            "Second SET is OK");
        
        $this->assertEquals($this->oo->get($key), "foo");
    }
    
    /* supersedes 004 */
    function testSet() {
        $key = $this->mk_key();
        $h = $this->handle;
        $cas1 = couchbase_set($h, $key, "bar");
        $cas2 = couchbase_set($h, $key, "bar");
        $this->assertNotEquals($cas1, $cas2);
        
        $rv = couchbase_set($h, $key, "foo", 0, $cas1);
        $this->assertFalse($rv);
        
        $this->assertEquals(couchbase_get($h, $key), "bar");
        
        $rv = couchbase_set($h, $key, "foo", 0, $cas2);
        $this->assertNotEmpty($rv);
        
        $this->assertEquals(couchbase_get($h, $key), "foo");
    }
    
    
    /* supersedes 011 */
    function testCasOO() {
        $oo = $this->oo;
        $key = $this->mk_key();
        $cas = $oo->add($key, "foo");
        $ncas = $oo->set($key, "bar");
        
        $this->assertFalse($oo->cas($cas, $key, "dummy"));
        $this->assertTrue($oo->cas($ncas, $key, "dummy", 1));
        
        $this->assertEquals($oo->get($key, NULL, $cas1),
                            "dummy");
        
        $this->assertNotEmpty($cas1);        
    }
    
    function testCas() {
        $h = $this->handle;
        $key = $this->mk_key();
        $cas = couchbase_add($h, $key, "foo");
        $ncas = couchbase_set($h, $key, "bar");
        
        $this->assertFalse(couchbase_cas($h, $cas, $key, "dummy"),
                           "Stale CAS returns false");
        $this->assertTrue(couchbase_cas($h, $ncas, $key, "dummy", 1),
                          "Valid CAS returns true");
        
        $this->assertEquals("dummy", couchbase_get($h, $key, NULL, $cas1),
                            "Got back expected value");        
    }
    
    function testFlush() {
        $this->markTestSkipped("Flush not working on server yet");
    }    
}

?>