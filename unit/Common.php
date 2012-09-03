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
    protected function setUp() {
        $this->handle = make_handle();
        $this->oo = make_handle_oo();
    }
    
    protected function mk_key() {
        $key = uniqid("couchbase_");
        $this->oo->delete($key);
        return $key;
    }

}

?>