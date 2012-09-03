<?php

require_once 'Common.php';
/**
 *Basic connection tests, replaces 002, 028 036
 */

class Connection extends PHPUnit_Framework_TestCase
{
    function testConnectBasic() {
        $handle = couchbase_connect(COUCHBASE_CONFIG_HOST,
                                    COUCHBASE_CONFIG_USER,
                                    COUCHBASE_CONFIG_PASSWD,
                                    COUCHBASE_CONFIG_BUCKET);
        
        $this->assertInternalType('resource', $handle, 'Couchbase Resource');
    }
    
    /**
     * We would like to be able to expect an exception, but this does not seem
     * possible currently.. (we don't have classes for exceptions)
     */
    function testConnectBad() {
        $handle = NULL;
        try {
            $handle = couchbase_connect(COUCHBASE_CONFIG_HOST,
                                        'bad_username',
                                        'bad_password',
                                        COUCHBASE_CONFIG_BUCKET);
        } catch (Exception $exc) {
            $this->assertRegExp("/auth/i", $exc->getMessage());
        }
        
        $this->assertNull($handle, "Bad connection parameters makes NULL handle");
    }
    
    function testConnectOO() {
        $handle = new Couchbase(COUCHBASE_CONFIG_HOST,
                                COUCHBASE_CONFIG_USER,
                                COUCHBASE_CONFIG_PASSWD,
                                COUCHBASE_CONFIG_BUCKET);
        $this->assertInstanceOf('Couchbase', $handle, 'Item is a Couchbase (OO)');
    }
    
    
    function testConnectUri() {
        $url = sprintf("htTp://%s:%s@%s/%s",
                        COUCHBASE_CONFIG_USER,
                        COUCHBASE_CONFIG_PASSWD,
                        COUCHBASE_CONFIG_HOST,
                        COUCHBASE_CONFIG_BUCKET);
        $handle = couchbase_connect($url);
        $this->assertInternalType('resource', $handle);
        $handle = new Couchbase($url);
        $this->assertInstanceOf('Couchbase', $handle);
        
    }
    
    function testConnectNodeArray() {
        $hosts = array('non-existent-host',
                       'another-bogus-host',
                       COUCHBASE_CONFIG_HOST);
        
        $cb = new Couchbase($hosts,
                            COUCHBASE_CONFIG_USER,
                            COUCHBASE_CONFIG_PASSWD,
                            COUCHBASE_CONFIG_BUCKET);
        $this->assertInstanceOf('Couchbase', $cb);
    }
    
}



?>