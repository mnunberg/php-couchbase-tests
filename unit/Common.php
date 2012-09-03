<?php

require_once 'PHPUnit.php';
require_once 'couchbase.inc';

function make_handle() {
    $handle = couchbase_connect(COUCHBASE_CONFIG_HOST,
                               COUCHBASE_CONFIG_USER,
                               COUCHBASE_CONFIG_PASSWD,
                               COUCHBASE_CONFIG_BUCKET);
    return $handle;
}

function make_handle_oo() {
    $oo = new Couchbase(COUCHBASE_CONFIG_HOST,
                        COUCHBASE_CONFIG_USER,
                        COUCHBASE_CONFIG_PASSWD,
                        COUCHBASE_CONFIG_BUCKET);
    return $oo;
}

class CouchbaseTestCommon extends PHPUnit_Framework_TestCase
{
    static $_handle = NULL;
    static $_oo = NULL;
    
    protected function setUp() {        
        $this->handle = make_handle();
        $this->oo = make_handle_oo();
    }
    
    protected function mk_key() {
        $key = uniqid("couchbase_");
        $this->oo->delete($key);
        return $key;
    }
    
    protected function getPersistHandle() {
        if (!self::$_handle) {
            self::$_handle = make_handle();
        }
        return self::$_handle;
    }
    
    protected function getPersistOO() {
        if (!self::$_oo) {
            print "Creating new handle\n";
            self::$_oo = make_handle_oo();
        }
        return self::$_oo;
    }
}

?>